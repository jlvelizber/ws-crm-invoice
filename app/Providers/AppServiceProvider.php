<?php

namespace App\Providers;

use App\RepositoryInterface\CustomerRepositoryInterface;
use App\Repository\
{
    CustomerRepository,
    InvoiceRepository,
    PlanRepository,
    UserRepository
};

use App\RepositoryInterface\{
    InvoiceRepositoryInterface,
    PlanRepositoryInterface,
    UserRepositoryInterface
};

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->bind(PlanRepositoryInterface::class, PlanRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
