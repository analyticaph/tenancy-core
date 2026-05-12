<?php

namespace TenancyCore\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OAuthClientApp extends Model
{
    use HasUuids;

    protected $table = 'oauth_client_apps';

    protected $guarded = [];

    public function oauthClient()
    {
        return $this->belongsTo(OAuthClient::class);
    }
}
