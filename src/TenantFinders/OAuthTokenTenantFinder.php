<?php

namespace TenancyCore\TenantFinders;

use Illuminate\Http\Request;
use Spatie\Multitenancy\TenantFinder\TenantFinder;
use TenancyCore\Models\Tenant;

class OAuthTokenTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        $token = $request->user()?->token();

        if (! $token || ! ($tenantId = $token->tenant_id)) {
            return null;
        }

        return Tenant::find($tenantId);
    }
}
