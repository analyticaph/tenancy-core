<?php

namespace TenancyCore\Tasks;

use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;
use TenancyCore\Models\Tenant;

class SwitchTenantDatabaseTask implements SwitchTenantTask
{
    public function makeCurrent(IsTenant $tenant): void
    {
        /** @var Tenant $tenant */
        $connection = config('multitenancy.tenant_database_connection_name');

        config([
            "database.connections.{$connection}.host" => $tenant->database_host,
            "database.connections.{$connection}.database" => $tenant->database_name,
            "database.connections.{$connection}.username" => $tenant->database_username,
            "database.connections.{$connection}.password" => $tenant->database_password,
        ]);

        app('db')->purge($connection);
        app('db')->reconnect($connection);
    }

    public function forgetCurrent(): void
    {
        $connection = config('multitenancy.tenant_database_connection_name');

        config([
            "database.connections.{$connection}.host" => null,
            "database.connections.{$connection}.database" => null,
            "database.connections.{$connection}.username" => null,
            "database.connections.{$connection}.password" => null,
        ]);

        app('db')->purge($connection);
    }
}
