<?php

namespace TenancyCore\Models;

use Spatie\Multitenancy\Models\Tenant as SpatieTenant;

class Tenant extends SpatieTenant
{
    protected $guarded = [];

    protected $casts = [
        'database_username' => 'encrypted',
        'database_password' => 'encrypted',
        'settings' => 'json',
    ];

    public function domains()
    {
        return $this->hasMany(TenantDomain::class);
    }

    public function oauthClient()
    {
        return $this->hasOne(OAuthClient::class);
    }

    public function primaryDomain(): ?TenantDomain
    {
        return $this->domains()->where('is_primary', true)->first();
    }
}
