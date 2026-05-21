<?php

namespace TenancyCore\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OAuthClientApp extends Model
{
    use HasFactory, HasUuids;

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

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, 'app_id');
    }
}
