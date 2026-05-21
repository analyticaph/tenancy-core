<?php

namespace Analyticaph\TenancyCore\Middleware;

use Closure;
use Illuminate\Http\Request;
use Analyticaph\TenancyCore\Exceptions\TenantNotFoundException;
use Analyticaph\TenancyCore\TenantFinders\OAuthTokenTenantFinder;

class ResolveTenantByOAuthToken
{
    public function __construct(private readonly OAuthTokenTenantFinder $finder) {}

    public function handle(Request $request, Closure $next): mixed
    {
        $tenant = $this->finder->findForRequest($request);

        if (! $tenant) {
            throw new TenantNotFoundException('Could not resolve tenant from OAuth token.');
        }

        $tenant->makeCurrent();

        return $next($request);
    }
}
