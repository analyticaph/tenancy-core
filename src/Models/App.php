<?php

namespace TenancyCore\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasUuids;

    protected $connection = 'landlord';

    protected $guarded = [];

    protected $casts = [
        'default_scopes' => 'array',
        'is_active' => 'bool',
    ];

    public function clientApps()
    {
        return $this->hasMany(OAuthClientApp::class, 'app_id');
    }
}
