<?php

namespace App\Providers;

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
        // Định dạng số sạch (Clean Number Formatting) cho toàn hệ thống
        // Sử dụng: @nfmt($value)
        \Illuminate\Support\Facades\Blade::directive('nfmt', function ($expression) {
            return "<?php echo \App\Helpers\Helper::nfmt($expression); ?>";
        });
    }
}
