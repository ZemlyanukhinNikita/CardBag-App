<?php

namespace App\Providers;

use App\AccessToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
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

        $this->app['auth']->viaRequest('api', function (Request $request) {

            if ($request->header('token')) {
                return AccessToken::where('name', $request->header('token'))->where('expires_at', '!=', null)
                    ->where('expires_at', '>', Carbon::now())->first()->user;
            }
        });
    }
}
