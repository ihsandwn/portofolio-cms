<?php

namespace App\Providers;

use App\Helpers\Sanitizer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        Blade::directive('safeHtml', function (string $expression): string {
            return "<?php echo App\Helpers\Sanitizer::clean($expression); ?>";
        });
    }
}
