<?php

namespace TenancyCore;

use Illuminate\Support\ServiceProvider;

class TenancyCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/tenancy-core.php', 'tenancy-core');
        $this->mergeConfigFrom(__DIR__.'/../config/multitenancy.php', 'multitenancy');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/tenancy-core.php' => config_path('tenancy-core.php'),
            __DIR__.'/../config/multitenancy.php' => config_path('multitenancy.php'),
        ], 'tenancy-core-config');

        $this->publishes([
            __DIR__.'/../config/multitenancy.php' => config_path('multitenancy.php'),
        ], 'multitenancy-config');
    }
}
