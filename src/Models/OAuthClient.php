<?php

namespace TenancyCore\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OAuthClient extends Model
{
    use HasUuids;

    protected $connection = 'landlord';

    protected $table = 'oauth_clients';

    protected $guarded = [];

    protected $casts = [
        'secret' => 'encrypted',
        'redirect_uris' => 'array',
        'grant_types' => 'array',
        'revoked' => 'bool',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function apps()
    {
        return $this->hasMany(OAuthClientApp::class, 'client_id');
    }
}
