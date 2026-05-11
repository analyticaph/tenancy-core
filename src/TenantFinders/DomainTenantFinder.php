<?php

namespace TenancyCore\TenantFinders;

use Spatie\Multitenancy\TenantFinder\DomainTenantFinder as SpatieDomainTenantFinder;

class DomainTenantFinder extends SpatieDomainTenantFinder
{
    // Inherits Spatie's domain-based resolution.
    // Override getCurrentDomain() here if subdomain extraction needs customising.
}
