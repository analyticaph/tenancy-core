<?php

namespace Analyticaph\TenancyCore\TenantFinders;

use Illuminate\Http\Request;
use Spatie\Multitenancy\TenantFinder\TenantFinder;
use Analyticaph\TenancyCore\Models\Tenant;
use Analyticaph\TenancyCore\Support\TenantCache;

class DomainTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        return TenantCache::forDomain($request->getHost());
    }
}
