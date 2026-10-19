<?php

namespace Analyticaph\TenancyCore;

use Illuminate\Database\Events\ConnectionEstablished;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Analyticaph\TenancyCore\Middleware\ResetPermissionCacheForTenant;
use Analyticaph\TenancyCore\Middleware\ResolveTenantByDomain;
use Analyticaph\TenancyCore\Middleware\ResolveTenantByOAuthToken;

class TenancyCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/tenancy-core.php', 'tenancy-core');
        $this->mergeConfigFrom(__DIR__ . '/../config/multitenancy.php', 'multitenancy');
    }

    public function boot(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('tenancy.domain', ResolveTenantByDomain::class);
        $router->aliasMiddleware('tenancy.token', ResolveTenantByOAuthToken::class);
        $router->aliasMiddleware('tenancy.permission-cache', ResetPermissionCacheForTenant::class);

        $this->configureStatementTimeout();

        $this->publishes([
            __DIR__ . '/../config/tenancy-core.php' => config_path('tenancy-core.php'),
            __DIR__ . '/../config/multitenancy.php' => config_path('multitenancy.php'),
        ], 'tenancy-core-config');

        $this->publishes([
            __DIR__ . '/../config/multitenancy.php' => config_path('multitenancy.php'),
        ], 'multitenancy-config');
    }

    /**
     * Cap how long a single query may run during web requests, so one slow query
     * can't hold a PHP-FPM worker until nginx gives up. Opt-in per connection via
     * a `statement_timeout` key (milliseconds, 0 disables) in config/database.php.
     * Console (queue workers, artisan) is exempt since imports and backfills may
     * legitimately run longer.
     */
    protected function configureStatementTimeout(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        Event::listen(function (ConnectionEstablished $event): void {
            $connection = $event->connection;
            $timeout = (int) $connection->getConfig('statement_timeout');

            // The tenant connection is resolved before a tenant is current; nothing to configure yet.
            if ($timeout <= 0 || $connection->getDriverName() !== 'pgsql' || blank($connection->getConfig('database'))) {
                return;
            }

            // SET can't take bound parameters; the value is an int cast from config.
            $connection->statement("SET statement_timeout = {$timeout}");
        });
    }
}
