<?php

namespace Analyticaph\TenancyCore\TenantFinders;

use Illuminate\Http\Request;
use Spatie\Multitenancy\TenantFinder\TenantFinder;
use Analyticaph\TenancyCore\Models\Tenant;
use Analyticaph\TenancyCore\Support\TenantCache;

class OAuthTokenTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        $token = $request->user()?->token();

        if (! $token || ! ($tenantId = $token->tenant_id)) {
            return null;
        }

        return TenantCache::find((string) $tenantId);
    }
}
