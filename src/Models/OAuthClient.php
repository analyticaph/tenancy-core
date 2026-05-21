<?php

namespace Analyticaph\TenancyCore\Models;

use Laravel\Passport\Client as PassportClient;

class OAuthClient extends PassportClient
{
    protected $connection = 'landlord';

    protected $table = 'oauth_clients';

    protected $casts = [
        'grant_types' => 'array',
        'scopes' => 'array',
        'redirect_uris' => 'array',
        'personal_access_client' => 'bool',
        'password_client' => 'bool',
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
