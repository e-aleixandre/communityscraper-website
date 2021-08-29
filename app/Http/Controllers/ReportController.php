<?php

namespace App\Http\Controllers;

use App\Mail\ReportCompleted;
use App\Mail\ReportErrored;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Facades\Http;
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

        // We didn't find a report, so we create a new one
        $report = $request->user()->reports()->create([
            'min_date' => $min_date,
            'max_date' => $max_date,
            'notify' => $validated['notify']
        ]);

        // TODO: Check this and see if it's overcomplicating things

        // Contacting to the API to instantiate the py exporter
        $apiResponse = Http::certified()->post(config('api.API_URL') . '/reports', [
            'id' => $report->id
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
     * @param Report $report
     * @return bool[]
     */
    public function send_notification(Report $report)
    {
        // NOTIFY
        if ($report->completed)
        {
            // If completed fetch the file and send it
            $responseObject = Http::certified()->get(config('api.API_URL') . '/reports', [
                'filename' => $report->filename
            ]);

            $file = $responseObject->body();

            Mail::to($report->user)->send(new ReportCompleted($report->filename, $file));
        }
        else  // If not completed just report the error
            Mail::to($report->user)->send(new ReportErrored());

        // Communicating to the API everything went OK
        return [
            'ok' => true
        ];
    }

    public function download_report(Report $report)
    {
        $apiResponse = Http::certified()->get(config('api.API_URL') . '/reports', [
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
        $apiResponse = Http::certified()->post(config('api.API_URL') . "/reports/stop", [
            'id' => $report->id
        ]);

        return Redirect::back()->with([
            'ok' => $apiResponse->ok()
        ]);
    }

    public function destroy(Report $report)
    {
        $apiResponse = Http::certified()->post(config('api.API_URL') . "/reports/destroy", [
            'id' => $report->id
        ]);

        return Redirect::back()->with([
            'ok' => $apiResponse->ok()
        ]);
    }

    public function restart(Report $report)
    {
        $apiResponse = Http::certified()->post(config('api.API_URL') . "/reports/restart", [
            'id' => $report->id
        ]);

        dd($apiResponse);
    }

}
