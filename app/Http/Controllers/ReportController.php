<?php

namespace App\Http\Controllers;

use App\Mail\ReportCompleted;
use App\Mail\ReportErrored;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::all();

        return Inertia::render('Reports/ListReports', [
            "reports" => $reports
        ]);
    }

    public function create()
    {
        return Inertia::render('Reports/CreateReport');
    }

    /**
     * Create a new report and fetch the endpoint to start the python exporter
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        // Validating the input
        $validated = $request->validate([
            'min_date' => ['date_format:Y-m-d', 'required'],
            'min_time' => ['date_format:H:i', 'required'],
            'max_date' => ['date_format:Y-m-d', 'required'],
            'max_time' => ['date_format:H:i', 'required'],
            'notify' => ['boolean']
        ]);

        // Transforming date + time in datetime string
        $min_date = "{$validated['min_date']}T{$validated['min_time']}";
        $max_date = "{$validated['max_date']}T{$validated['max_time']}";

        // Checking currently running reports
        $currentReports = Report::where([
            'completed' => false,
            'errored' => false
        ])->count();

        // Return an error if a report is already running
        // TODO: Manage queues or something so another report can be added
        if ($currentReports > 0) {
            return "Current reports: $currentReports";
        }

        // Check if there's a completed report with those dates
        $report = Report::where([
            'min_date' => $min_date,
            'max_date' => $max_date,
            'completed' => true
        ])->count();

        if ($report) {
            return Redirect::back()->with([
                'ok' => false,
                'type' => 'warning',
                'message' => 'Ya existe un informe con esas fechas de inicio y final, y parece que se generó correctamente.
                 Revisa la lista de informes. Si crees que es un error, contacta con el administrador.'
            ]);
        }

        // Create the report and a token before contacting the API
        $token = Str::random(32);

        $request->user()->reports()->create([
            'min_date' => $min_date,
            'max_date' => $max_date,
            'token' => $token,
            'notify' => $validated['notify']
        ]);

        // Contacting to the API to instantiate the py exporter
        $apiResponse = Http::post(config('api.API_URL') . '/reports', [
            'token' => $token
        ]);

        // Create the response array
        $responseArray = [
            'ok' => $apiResponse->object()->ok
        ];

        // Populate the response array if there was an error
        if (!$apiResponse->object()->ok) {
            $responseArray['message'] = 'Ha habido un error al crear el informe. Vuelve a intentarlo en unos segundos. Si después de varios
                intentos aún no puedes generar informes contacta con el administrador.';

            $responseArray['type'] = 'danger';
        }

        // Return the response array
        return redirect()->back()->with($responseArray);
    }

    /**
     * Endpoint for the API to connect and trigger a notification when a report finishes o errors
     *
     * @param Request $request
     * @return bool[]
     */
    public function send_notification(Request $request)
    {

        // Get the query param
        $token = $request->query('token');

        // Find the report with that token or throw a 404
        $report = Report::where('token', $token)->firstOrFail();

        // Remove the token (only notify once)
        $report->token = null;

        // Update the model
        $report->save();

        // Fetch the file
        $responseObject = Http::get(config('api.API_URL') . '/reports', [
            'filename' => $report->filename
        ]);

        $file = $responseObject->body();

        // NOTIFY
        if ($report->completed)
            Mail::to($report->user)->send(new ReportCompleted($report->filename, $file));
        else
            Mail::to($report->user)->send(new ReportErrored());

        // Communicating to the API everything went OK
        return [
            'ok' => true
        ];
    }

    public function download_report(Report $report)
    {
        $apiResponse = Http::get(config('api.API_URL') . '/reports', [
            'filename' => $report->filename
        ]);

        if (!$apiResponse->ok())
            abort(404);

        return response()->streamDownload(function () use ($apiResponse) {
            echo $apiResponse->body();
        }, $report->filename, [
            'Content-Type' =>  'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $report->filename .'"'
        ]);
    }

    public function stop_report(Report $report)
    {
        $token = Str::random(32);

        $report->update([
            'token' => $token
        ]);

        $apiResponse = Http::post(config('api.API_URL') . "/reports/$report->id/stop", [
            'token' => $token
        ]);

        return Redirect::back()->with([
            'ok' => $apiResponse->ok()
        ]);
    }

    public function destroy(Report $report)
    {
        $token = Str::random(32);

        $report->update([
            'token' => $token
        ]);

        $apiResponse = Http::post(config('api.API_URL') . "/reports/$report->id/destroy", [
            'token' => $token
        ]);

        return Redirect::back()->with([
            'ok' => $apiResponse->ok()
        ]);
    }
}
