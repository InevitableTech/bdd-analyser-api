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

$router->group(['prefix' => 'user'], function () use ($router) {
    $router->post('/', 'UserController@create');
    $router->get('/id/{email}', 'UserController@getId');
});

$router->group(['prefix' => 'token'], function () use ($router) {
    $router->post('/', 'TokenController@create');
});

$router->group(['middleware' => 'authenticate:api'], function () use ($router) {
    $router->group(['prefix' => 'analysis'], function () use ($router) {
        $router->post('/', 'AnalysisController@create');
        $router->get('/{id}', 'AnalysisController@find');
        $router->get('/', 'AnalysisController@findAll');
        $router->put('/{id}', 'AnalysisController@update');
        $router->delete('/{id}', 'AnalysisController@delete');
    });

    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->get('/', 'UserController@findAll');
        $router->get('/{id}', 'UserController@find');
        $router->put('/{id}', 'UserController@update');
        $router->delete('/{id}', 'UserController@delete');
    });

    $router->group(['prefix' => 'project'], function () use ($router) {
        $router->post('/', 'ProjectController@create');
        $router->get('/{id}', 'ProjectController@find');
        $router->get('/', 'ProjectController@findAll');
        $router->put('/{id}', 'ProjectController@update');
        $router->delete('/{id}', 'ProjectController@delete');
    });

    $router->group(['prefix' => 'organisation'], function () use ($router) {
        $router->post('/', 'OrganisationController@create');
        $router->get('/{id}', 'OrganisationController@find');
        $router->get('/', 'OrganisationController@findAll');
        $router->put('/{id}', 'OrganisationController@update');
        $router->delete('/{id}', 'OrganisationController@delete');
    });

    $router->group(['prefix' => 'token'], function () use ($router) {
        $router->get('/', 'TokenController@findAll');
        $router->put('/{id}', 'TokenController@update');
        $router->post('/refresh', 'TokenController@refresh');
    });
});
