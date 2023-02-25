<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseMigrations;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function includeRoutes($version)
    {
        $namespace = 'App\Http\Controllers\\' . strtoupper($version);
        $router = $this->app['router'];

        // Force include the routing file.
        $this->app['router']->group(['namespace' => $namespace], function ($router) use ($version) {
            require app()->basePath('routes') . '/api_' . $version . '.php';
        });
    }
}
