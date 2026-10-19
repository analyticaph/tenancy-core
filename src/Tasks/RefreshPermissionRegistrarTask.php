<?php

namespace Analyticaph\TenancyCore\Tasks;

use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;
use Spatie\Permission\PermissionRegistrar;

/**
 * Spatie\Permission\PermissionRegistrar is a container singleton that captures
 * its cache repository (and so the cache prefix) in its constructor. If it was
 * resolved before a tenant switch — e.g. by Spatie's callAfterResolving(Gate)
 * hook — it keeps reading and writing the permission cache under the previous
 * prefix and DB connection.
 *
 * Dropping the instance lets the next resolve build a fresh registrar against
 * the switched cache manager. Nothing else holds on to it: the Gate `before`
 * closure and the HasRoles/HasPermissions traits resolve it from the
 * container on each use. The cached permission map itself is left intact, so
 * it keeps serving later requests for the same tenant.
 *
 * Register after PrefixCacheTask in multitenancy.switch_tenant_tasks.
 * No-op in apps that don't use spatie/laravel-permission.
 */
class RefreshPermissionRegistrarTask implements SwitchTenantTask
{
    public function makeCurrent(IsTenant $tenant): void
    {
        self::reset();
    }

    public function forgetCurrent(): void
    {
        self::reset();
    }

    public static function reset(): void
    {
        if (class_exists(PermissionRegistrar::class) && app()->resolved(PermissionRegistrar::class)) {
            app()->forgetInstance(PermissionRegistrar::class);
        }
    }
}
