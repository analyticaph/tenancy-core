<?php

namespace Analyticaph\TenancyCore\Middleware;

use Analyticaph\TenancyCore\Tasks\RefreshPermissionRegistrarTask;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Drops a PermissionRegistrar resolved before tenant resolution so the rest of
 * the request reads permissions under the tenant's cache prefix. See
 * RefreshPermissionRegistrarTask for the full rationale.
 *
 * This used to call forgetCachedPermissions(), which deleted the shared
 * permission cache on every request and forced a full permissions/roles
 * reload from the DB each time. It now only resets the in-memory instance.
 *
 * @deprecated Add RefreshPermissionRegistrarTask to
 *             multitenancy.switch_tenant_tasks (after PrefixCacheTask) instead;
 *             that also covers queue workers and tenants:artisan.
 */
class ResetPermissionCacheForTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        RefreshPermissionRegistrarTask::reset();

        return $next($request);
    }
}
