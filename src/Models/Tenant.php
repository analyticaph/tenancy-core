<?php

namespace TenancyCore\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;

class Tenant extends SpatieTenant
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'database_username' => 'encrypted',
        'database_password' => 'encrypted',
        'settings' => 'json',
    ];

    public function getDatabaseName(): string
    {
        return $this->database_name;
    }

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
