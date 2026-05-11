<?php

namespace TenancyCore\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Http\Middleware\NeedsTenant;

class ResolveTenantByDomain extends NeedsTenant
{
    public function handle(Request $request, Closure $next): mixed
    {
        return parent::handle($request, $next);
    }
}
