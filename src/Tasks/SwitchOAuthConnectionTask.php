<?php

namespace TenancyCore\Tasks;

use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SwitchOAuthConnectionTask implements SwitchTenantTask
{
    public function makeCurrent(IsTenant $tenant): void
    {
        // TODO: point the oauth token/refresh-token connection at the tenant DB
        // when OAuth tables live per-tenant rather than on the landlord.
    }

    public function forgetCurrent(): void
    {
        // TODO: reset the OAuth connection back to landlord/default.
    }
}
