<?php

namespace TenancyCore\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OAuthClientApp extends Model
{
    use HasUuids;

    protected $connection = 'landlord';

    protected $table = 'oauth_client_apps';

    protected $guarded = [];

    protected $casts = [
        'redirect_uris' => 'array',
        'scopes' => 'array',
        'is_active' => 'bool',
    ];

    public function oauthClient()
    {
        return $this->belongsTo(OAuthClient::class, 'client_id');
    }
}
