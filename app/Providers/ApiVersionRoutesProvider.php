<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\ServiceProvider;

class ApiVersionRoutesProvider extends ServiceProvider
{
    private array $ignoreRequestsFromUserAgent = ['symfony', ''];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        // Ignore this process when calling the CLI.
        if (! in_array(strtolower($this->app['request']->userAgent()), $this->ignoreRequestsFromUserAgent)) {
            $version = $this->app['request']->header('Accept-Version');

            switch ($version) {
                case 'v1':
                    $namespace = 'App\Http\Controllers\V1';
                    break;
                default:
                    throw new Exception(
                        'Unsupported version or missing version in header.'
                    );
            }

            $this->app['router']->group([
                'middleware' => 'authorise',
                'namespace' => $namespace
            ], function ($router) use ($version) {
                require app()->basePath('routes') . '/api_' . $version . '.php';
            });
        }
    }
}
