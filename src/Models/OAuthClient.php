<?php

namespace Analyticaph\TenancyCore\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Client as PassportClient;
use Analyticaph\TenancyCore\Casts\SharedEncrypted;

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
        'secret_plaintext' => SharedEncrypted::class,
    ];

    public function skipsAuthorization(Authenticatable $user, array $scopes): bool
    {
        return true;
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function apps()
    {
        return $this->hasMany(OAuthClientApp::class, 'client_id');
    }

}
