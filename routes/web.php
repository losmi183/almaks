<?php

use Carbon\Carbon;
use App\Models\Report;
use App\Models\Product;
use App\Services\NisReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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

    // Product::create([
    //     'name' => 'air max',
    //     'category' => 'tikepa',
    //     'price' => 15999
    // ]);

    return view('welcome');
});

Route::get('/report', function() {

    // CREATE NEW OBJECT
    $nisReport = new NisReport();
    // LOGIN METHOD RETURN TOKEN FOR NEXT REQUEST
    $token = $nisReport->login();
    // CALL getReport with new token
    $allReports = $nisReport->getReport($token);
        
    // Insert into DB
    $nisReport->saveReports($allReports);

    return 'Data saved to database';

});


Route::get('/carbon', function() {

    dd(Carbon::now()->timestamp . '000');

    $current = Carbon::now()->timestamp;

    $yesterday = $current - (24 * 60 * 60);

    dd($yesterday . '000');

});