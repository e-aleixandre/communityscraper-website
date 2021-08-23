<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function send_notification(Request $request) {
        // Get the query param
        $token = $request->query('token');

        // Find the report with that token or throw a 404
        $report = Report::where('token', $token)->firstOrFail();

        // Remove the token (only notify once)
        $report->token = null;
        $report->progress = 69.0;
        // Update the model
        $report->save();

        return [
            "ok" => true
        ];
    }
}
