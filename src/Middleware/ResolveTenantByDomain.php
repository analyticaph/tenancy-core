<?php

namespace TenancyCore\Middleware;

use Closure;
use Illuminate\Http\Request;
use TenancyCore\Exceptions\TenantNotFoundException;
use TenancyCore\TenantFinders\DomainTenantFinder;

class ResolveTenantByDomain
{
    public function __construct(private readonly DomainTenantFinder $finder) {}

    public function handle(Request $request, Closure $next): mixed
    {
        $tenant = $this->finder->findForRequest($request)
            ?? throw new TenantNotFoundException("No tenant for host: {$request->getHost()}");

        $tenant->makeCurrent();

        return $next($request);
    }
}
