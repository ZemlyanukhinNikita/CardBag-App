<?php

namespace App\Providers;

use app\Repositories\AccessTokenInterface;
use app\Repositories\AccessTokenRepository;
use app\Repositories\CardInterface;
use app\Repositories\CardRepository;
use app\Repositories\CategoryInterface;
use app\Repositories\CategoryRepository;
use app\Repositories\NetworkInterface;
use app\Repositories\NetworkRepository;
use app\Repositories\PhotoInterface;
use app\Repositories\PhotoRepository;
use app\Repositories\RefreshTokenInterface;
use app\Repositories\RefreshTokenRepository;
use app\Repositories\TokenInterface;
use app\Repositories\TokenRepository;
use App\Repositories\UserDataInterface;
use App\Repositories\UserDataRepository;
use app\Repositories\UserInterface;
use App\Repositories\UserNetworkInterface;
use App\Repositories\UserNetworkRepository;
use app\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Wn\Generators\CommandsServiceProvider');
            $this->app->singleton('RobbieP\ZbarQrdecoder\ZbarQrdecoderServiceProvider');
        }

        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(AccessTokenInterface::class, AccessTokenRepository::class);
        $this->app->bind(RefreshTokenInterface::class, RefreshTokenRepository::class);
        $this->app->bind(CardInterface::class, CardRepository::class);
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(PhotoInterface::class, PhotoRepository::class);
        $this->app->bind(NetworkInterface::class, NetworkRepository::class);
        $this->app->bind(UserNetworkInterface::class, UserNetworkRepository::class);
        $this->app->bind(Firebase::class, function () {
            $serviceAccount = new ServiceAccount();
            $firebase = new Factory();
            return $firebase->withServiceAccount($serviceAccount->fromJsonFile('../CARDbag-d01707728926.json'))->create();
        });
    }
}