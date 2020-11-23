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

Route::get('/', function () {
    return view('welcome');
});

//Simply upload  pictures about Baoguo
Route::get('/upload-file', 'FileUpload@createForm');
Route::post('/upload-file', 'FileUpload@fileUpload')->name('fileUpload');
Route::get('/check-file', 'FileUpload@view');
Route::post('/check-file', 'FileUpload@fileCheck')->name('fileCheck');


//Or you can simply join HYXY_TaiChi by submitting your resume in json XD
Route::get('/check', 'TaskController@index');
Route::post('/init', 'TaskController@create');
Route::post('/edit', 'TaskController@edit');
