<?php

namespace TenancyCore\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthClientApp extends Model
{
    protected $guarded = [];

    public function oauthClient()
    {
        return $this->belongsTo(OAuthClient::class);
    }
}
