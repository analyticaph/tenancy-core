<?php

namespace TenancyCore\Models;

use Illuminate\Database\Eloquent\Model;

class TenantDomain extends Model
{
    protected $guarded = [];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
