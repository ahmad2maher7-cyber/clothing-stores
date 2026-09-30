<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ═══════════════════════════════════════════
        //  Force HTTPS for all URLs
        //  Required for Render / any HTTPS proxy
        // ═══════════════════════════════════════════
        
        // Force HTTPS scheme
        URL::forceScheme('https');
        
        // Force root URL
        if ($rootUrl = config('app.url')) {
            URL::forceRootUrl($rootUrl);
        }
    }
}