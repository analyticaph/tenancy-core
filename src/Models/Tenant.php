<?php

namespace TenancyCore\Models;

use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;

class Tenant extends SpatieTenant
{
    /** @use HasFactory<TenantFactory> */
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'database_username' => 'encrypted',
        'database_password' => 'encrypted',
        'settings' => 'json',
    ];

    protected static function newFactory(): TenantFactory
    {
        return TenantFactory::new();
    }

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
