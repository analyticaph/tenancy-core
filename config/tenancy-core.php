<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tenant Model
    |--------------------------------------------------------------------------
    | The Eloquent model used as the canonical Tenant representation across
    | all SmartCampus apps. Must extend SpatieTenant.
    */
    'tenant_model' => \Analyticaph\TenancyCore\Models\Tenant::class,

    /*
    |--------------------------------------------------------------------------
    | Shared Encryption Key
    |--------------------------------------------------------------------------
    | Used to encrypt/decrypt sensitive tenant fields (database_username,
    | database_password) consistently across all apps in the ecosystem.
    | Set TENANT_DB_KEY to the same base64-encoded 32-byte key in every app.
    | Generate once with: php artisan tenancy:generate-shared-key
    */
    'shared_encryption_key' => env('TENANT_DB_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Tenant Finders
    |--------------------------------------------------------------------------
    | domain  — resolves tenant from the request subdomain/domain.
    | token   — resolves tenant from the OAuth access token's tenant_id claim.
    */
    'finders' => [
        'domain' => \Analyticaph\TenancyCore\TenantFinders\DomainTenantFinder::class,
        'token'  => \Analyticaph\TenancyCore\TenantFinders\OAuthTokenTenantFinder::class,
    ],

];
