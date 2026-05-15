<?php

namespace TenancyCore\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantApp extends Model
{
    use HasUuids;

    protected $connection = 'landlord';

    protected $table = 'tenant_apps';

    protected $guarded = [];

    protected $casts = [
        'is_enabled' => 'bool',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }
}
