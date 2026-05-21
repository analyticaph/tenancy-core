<?php

namespace TenancyCore\TenantFinders;

use Illuminate\Http\Request;
use Spatie\Multitenancy\TenantFinder\TenantFinder;
use TenancyCore\Models\Tenant;
use TenancyCore\Models\TenantDomain;

class DomainTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        $host = $request->getHost();

        $domain = TenantDomain::where('domain', $host)
            ->with('tenant')
            ->first();

        return $domain?->tenant;
    }
}
