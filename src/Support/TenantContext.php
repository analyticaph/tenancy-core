<?php

namespace Analyticaph\TenancyCore\Support;

use Analyticaph\TenancyCore\Models\Tenant;

class TenantContext
{
    public static function current(): ?Tenant
    {
        /** @var Tenant|null */
        return Tenant::current();
    }

    public static function currentOrFail(): Tenant
    {
        return self::current() ?? throw new \RuntimeException('No current tenant.');
    }
}
