<?php

namespace Analyticaph\TenancyCore\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class App extends Model
{
    use HasFactory, HasUuids;

    protected $connection = 'landlord';

    protected $guarded = [];

    protected $casts = [
        'default_scopes' => 'array',
        'is_active' => 'bool',
    ];

    public function clientApps(): HasMany
    {
        return $this->hasMany(OAuthClientApp::class, 'app_id');
    }

    public function tenantApps(): HasMany
    {
        return $this->hasMany(TenantApp::class, 'app_id');
    }
}
