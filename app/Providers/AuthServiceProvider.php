<?php

namespace App\Providers;

use App\Models\Token;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Crypt;

class AuthServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('token', function ($request) {
            if ($request->header('user_token')) {
                return User::whereHas(
                    'tokens',
                    fn ($query) => $query
                        ->where('token', '=', $request->header('user_token'))
                            ->where('expires_on', '>', new \DateTime())
                )->firstOrFail();
            }
        });
    }
}
