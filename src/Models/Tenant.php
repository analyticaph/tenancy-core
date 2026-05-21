<?php

namespace Analyticaph\TenancyCore\Models;

use App\Models\User;
use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;
use Analyticaph\TenancyCore\Casts\SharedEncrypted;

class Tenant extends SpatieTenant
{
    /** @use HasFactory<TenantFactory> */
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'database_username' => SharedEncrypted::class,
        'database_password' => SharedEncrypted::class,
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

    public function tenantApps(): HasMany
    {
        return $this->hasMany(TenantApp::class);
    }

    public function staff()
    {
        return $this->belongsToMany(User::class, 'tenant_user', 'tenant_id', 'user_id')
            ->withPivot('role', 'joined_at');
    }
}
