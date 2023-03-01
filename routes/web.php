<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Services\ApiVersionService;

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

$router->get('/', function () use ($router) {
    return app()->version();
});

/**
 * We set a placeholder in the namespace that we will later resolve in the middle layer with the
 * request version number.
 */
$router->group([
    'middleware' => ['authorise', 'api_versioning'],
    'namespace' => 'App\Http\Controllers\\' . ApiVersionService::PLACEHOLDER
], function ($router) {
    require __DIR__ . '/api_v1.php';
});
