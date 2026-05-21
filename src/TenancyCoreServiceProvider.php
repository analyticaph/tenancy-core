<?php

namespace TenancyCore;

use Illuminate\Support\ServiceProvider;
use TenancyCore\Middleware\ResolveTenantByDomain;
use TenancyCore\Middleware\ResolveTenantByOAuthToken;

class TenancyCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/tenancy-core.php', 'tenancy-core');
        $this->mergeConfigFrom(__DIR__.'/../config/multitenancy.php', 'multitenancy');
    }

    public function boot(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('tenancy.domain', ResolveTenantByDomain::class);
        $router->aliasMiddleware('tenancy.token', ResolveTenantByOAuthToken::class);

        $this->publishes([
            __DIR__.'/../config/tenancy-core.php' => config_path('tenancy-core.php'),
            __DIR__.'/../config/multitenancy.php' => config_path('multitenancy.php'),
        ], 'tenancy-core-config');

        $this->publishes([
            __DIR__.'/../config/multitenancy.php' => config_path('multitenancy.php'),
        ], 'multitenancy-config');
    }
}
