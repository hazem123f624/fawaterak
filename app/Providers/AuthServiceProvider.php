<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        //
    ];


    public function boot(): void
    {
//        $this->registerPolicies();
//
//        Gate::before(function ($user, $ability) {
//            return $user->hasRole('admin') ? true : null;
//        });
    }

}
