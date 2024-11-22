<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AppointmentService;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         // Bind the AppointmentService to the service container
         $this->app->singleton(AppointmentService::class, function ($app) {
            return new AppointmentService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
         //
         $helperPath = app_path('Helpers/StripeHelper.php');
         
         if (file_exists($helperPath)) {
             require_once $helperPath;
         } else {
             Log::error("stripeHelper.php not found at: " . $helperPath);
         }

         $helperPath2 = app_path('Helpers/helpers.php');
         
         if (file_exists($helperPath2)) {
             require_once $helperPath2;
         } else {
             Log::error("helpers.php not found at: " . $helperPath2);
         }
         
         $helperPath3 = app_path('Helpers/SmsHelper.php');
         
         if (file_exists($helperPath3)) {
             require_once $helperPath3;
         } else {
             Log::error("SmsHelper.php not found at: " . $helperPath3);
         }
         
    }
}
