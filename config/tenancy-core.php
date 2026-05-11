<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tenant Model
    |--------------------------------------------------------------------------
    | The Eloquent model used as the canonical Tenant representation across
    | all SmartCampus apps. Must extend SpatieTenant.
    */
    'tenant_model' => \TenancyCore\Models\Tenant::class,

    /*
    |--------------------------------------------------------------------------
    | Tenant Finders
    |--------------------------------------------------------------------------
    | domain  — resolves tenant from the request subdomain/domain.
    | token   — resolves tenant from the OAuth access token's tenant_id claim.
    */
    'finders' => [
        'domain' => \TenancyCore\TenantFinders\DomainTenantFinder::class,
        'token'  => \TenancyCore\TenantFinders\OAuthTokenTenantFinder::class,
    ],

];
