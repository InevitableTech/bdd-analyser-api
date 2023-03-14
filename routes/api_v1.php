<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

Route::group(['prefix' => 'user'], function () {
    Route::get('/id/{email}', 'UserController@getId');
    Route::post('/auth', 'UserController@auth');
});

Route::group(['middleware' => 'authenticate:api'], function () {
    Route::group(['prefix' => 'analysis'], function () {
        Route::post('/', 'AnalysisController@create');
        Route::get('/{id}', 'AnalysisController@find');
        Route::get('/', 'AnalysisController@findAll');
        Route::put('/{id}', 'AnalysisController@update');
        Route::delete('/{id}', 'AnalysisController@delete');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::post('/', 'UserController@create');
        Route::get('/', 'UserController@findAll');
        Route::get('/{id}', 'UserController@find');
        Route::put('/{id}', 'UserController@update');
        Route::delete('/{id}', 'UserController@delete');
    });

    Route::group(['prefix' => 'project'], function () {
        Route::post('/', 'ProjectController@create');
        Route::get('/{id}', 'ProjectController@find');
        Route::get('/', 'ProjectController@findAll');
        Route::put('/{id}', 'ProjectController@update');
        Route::delete('/{id}', 'ProjectController@delete');
    });

    Route::group(['prefix' => 'organisation'], function () {
        Route::post('/', 'OrganisationController@create');
        Route::get('/{id}', 'OrganisationController@find');
        Route::get('/', 'OrganisationController@findAll');
        Route::put('/{id}', 'OrganisationController@update');
        Route::delete('/{id}', 'OrganisationController@delete');
    });

    Route::group(['prefix' => 'token'], function () {
        Route::get('/', 'TokenController@findAll');
        Route::put('/{id}', 'TokenController@update');
        Route::post('/refresh', 'TokenController@refresh');
        Route::post('/', 'TokenController@create');
    });
});
