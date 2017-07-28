<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Customer', \App\Services\CustomerService::class);
        $this->app->bind('Loan', \App\Services\LoanService::class);
        $this->app->bind('Screen', \App\Services\ScreenService::class);
        $this->app->bind('Message', \App\Services\MessageService::class);
        $this->app->bind('Payment', \App\Services\PaymentService::class);
        
    }
}
