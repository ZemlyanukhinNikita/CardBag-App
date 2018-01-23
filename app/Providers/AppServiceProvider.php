<?php

namespace App\Providers;

use app\Repositories\CardInterface;
use app\Repositories\CardRepository;
use app\Repositories\CategoryInterface;
use app\Repositories\CategoryRepository;
use app\Repositories\UserInterface;
use app\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

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

        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(CardInterface::class, CardRepository::class);
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
    }
}