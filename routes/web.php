<?php

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

Route::get('/', function() {

    return view('welcome');
});


Route::get('/chart', function() {


    $records_table = DB::table('records')
                            ->where(function($query)
                            {
                                $query->where('status_type', 'like', 'Closed - %')
                                ->where('record_type', '<>', 'Investigation')
                                ;
                            })
                            ->orderBy('compliance_due_date')
                            ->get();

    return view('chart', ['records' => $records_table]);
});
