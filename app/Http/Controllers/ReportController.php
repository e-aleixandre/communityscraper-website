<?php

namespace App\Http\Controllers;

use App\Mail\ReportCompleted;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Create a new report and fetch the endpoint to start the python exporter
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'min_date' => ['date_format:Y-m-d', 'required'],
            'min_time' => ['date_format:H:i', 'required'],
            'max_date' => ['date_format:Y-m-d', 'required'],
            'max_time' => ['date_format:H:i', 'required']
        ]);

        // Transforming date + time in datetime string
        $min_date = "{$validated['min_date']}T{$validated['min_time']}";
        $max_date = "{$validated['max_date']}T{$validated['max_time']}";

        $currentReports = Report::where([
            "completed" => false,
            "errored" => false
        ])->count();

        // Return an error if a report is already running
        // TODO: Manage queues or something so another report can be added
        if ($currentReports > 0) {
            return "Current reports: $currentReports";
        }

        // Check if there's a completed report with those dates
        $report = Report::where([
            "min_date" => $min_date,
            "max_date" => $max_date,
            "completed" => true
        ])->count();

        if ($report) {
            return "Report already exists and it's completed";
        }

        // Create the report and a token before contacting the API
        $token = Str::random(32);

        $report = Report::create([
            "min_date" => $min_date,
            "max_date" => $max_date,
            "token" => $token
        ]);

        // Contacting to the API to instantiate the py exporter
        $apiResponse = Http::post('http://localhost:3000/reports', [
            "token" => $token
        ]);

        // Checking the response
        if ($apiResponse->object()->ok) {
            Mail::to("test@email.com")->send(new ReportCompleted());
            return "ok";
        } else {
            return "Error";
        }
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

        // NOTIFY

        return [
            "ok" => true
        ];
    }

    public function download_report(Report $report)
    {
        /*
        $apiResponse = Http::get('http://localhost:3000/reports', [
            "filename" => $report->filename
        ]);

        $responseObject = $apiResponse->object();

        //dd($responseObject);

        if ($responseObject) {
            return response()->streamDownload(function () use ($responseObject) {
                echo $responseObject->data->stream;
            }, $report->filename);
        } else {
            abort(404);
        }
        */
    }
}
