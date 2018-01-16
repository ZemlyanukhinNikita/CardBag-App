<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Repository\CardRepository;
use Repository\RepositoryInterface;

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
        }
        $this->app->bind(RepositoryInterface::class, CardRepository::class);
    }
}