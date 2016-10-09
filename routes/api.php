<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('sessions', 'SessionsController@store');

Route::post('users', 'UsersController@store');

Route::get('faculties', 'FacultiesController@index');
Route::get('faculties/{faculty}', 'FacultiesController@show');

Route::get('subjects', 'SubjectsController@index');
Route::get('subjects/{subject}', 'SubjectsController@show');

Route::get('lectures', 'LecturesController@index');
Route::get('lectures/{lecture}', 'LecturesController@show');

Route::get('questions', 'QuestionsController@index');
Route::get('questions/{question}', 'QuestionsController@show');
Route::post('questions', 'QuestionsController@store');

Route::group(['middleware' => 'auth'], function () {
    Route::delete('sessions', 'SessionsController@destroy');

    Route::put('users/{user}', 'UsersController@update');

    Route::post('subjects', 'SubjectsController@store');

    Route::post('lectures', 'LecturesController@store');
    Route::delete('lectures/{lecture}', 'LecturesController@destroy');

    Route::put('questions/{question}', 'QuestionsController@update');
    Route::delete('questions/{question}', 'QuestionsController@destroy');
});