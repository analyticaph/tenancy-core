<?php

namespace TenancyCore\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthClient extends Model
{
    protected $guarded = [];

    protected $casts = [
        'secret' => 'encrypted',
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
        return $this->hasMany(OAuthClientApp::class);
    }
}
