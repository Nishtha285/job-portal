<?php

namespace App\Providers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('apply-job', function (User $user, Job $job) {
            return $user->id !== $job->user_id; // not own job
        });

        Gate::define('isLogin', function () {
            return Auth::check();
        });
        Paginator::useBootstrapFive();
    }
}
