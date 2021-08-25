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
| contains the 'web' middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/**
 * REPORTS
 */
Route::get('/reports', [ReportController::class, 'index'])->middleware('auth')->name('reports.index');
// TODO: Is it more reasonable to use a post request?
Route::get('/reports/notify', [ReportController::class, 'send_notification']);
Route::post('/reports', [ReportController::class, 'store'])->middleware('auth')->name('reports.store');
Route::get('/reports/create', [ReportController::class, 'create'])->middleware('auth')->name('reports.create');
Route::get('/reports/{report}/download', [ReportController::class, 'download_report'])->middleware('auth')->name('reports.download');

require __DIR__ . '/auth.php';
