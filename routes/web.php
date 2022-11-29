<?php

use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('reports', 'ReportController');
Route::post('/reports/search', 'ReportController@search')->name('reports.search');
Route::post('/reports/pdf', 'ReportController@exportPDF')->name('reports.pdf');
Route::get('/reports/{reportId}/status', 'ReportController@status')->name('reports.status');
Route::get('/reports/{reportId}/download', 'ReportController@download')->name('reports.download');


