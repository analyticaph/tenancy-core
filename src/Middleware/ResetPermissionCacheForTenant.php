<?php

namespace Analyticaph\TenancyCore\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

/**
 * Spatie\Permission\PermissionRegistrar is a container singleton whose cache
 * repository reference is captured once, in its constructor. If anything
 * resolves Gate (or the registrar itself) before tenant resolution has run
 * — e.g. Spatie's own callAfterResolving(Gate::class, ...) hook firing on
 * whatever first touches Gate — the registrar's cached permission-role map
 * gets built (and stored) against the pre-tenant-switch DB connection and
 * cache prefix, and stays wrong for the rest of the request regardless of
 * `permission:cache-reset` run elsewhere (a separate process/request).
 *
 * Register immediately after ResolveTenantByDomain /
 * ResolveTenantOAuthCredentials so every permission check for the rest of
 * the request reads fresh from the correct tenant DB. No-op in apps that
 * don't use spatie/laravel-permission.
 */
class ResetPermissionCacheForTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }

        return $next($request);
    }
}
