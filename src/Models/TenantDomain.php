<?php

namespace Analyticaph\TenancyCore\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TenantDomain extends Model
{
    use HasUuids;

    protected $connection = 'landlord';

    protected $guarded = [];

    protected $casts = [
        'is_primary' => 'bool',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
