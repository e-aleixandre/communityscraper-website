<?php

use App\Http\Controllers\ReportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Report;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/**
 * REPORTS
 */
Route::get('/reports/notify', [ReportController::class, "send_notification"]);
// TODO: Remove withoutMiddleware !
Route::post('/reports', [ReportController::class, "store"])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

require __DIR__.'/auth.php';
