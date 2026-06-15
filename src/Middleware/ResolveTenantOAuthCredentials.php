<?php

namespace Analyticaph\TenancyCore\Middleware;

use Analyticaph\TenancyCore\Exceptions\AppNotProvisionedException;
use Analyticaph\TenancyCore\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantOAuthCredentials
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = TenantContext::currentOrFail();

        $oauthClient = $tenant->oauthClient
            ?? throw new AppNotProvisionedException(
                "Tenant {$tenant->getKey()} has no OAuth client provisioned."
            );

        $appSlug = (string) config('oauth-client.app_slug');

        $clientApp = $oauthClient->apps()
            ->whereHas('app', fn($query) => $query->where('slug', $appSlug))
            ->where('is_active', true)
            ->first()
            ?? throw new AppNotProvisionedException(
                "Tenant {$tenant->getKey()} is not provisioned for app '{$appSlug}'."
            );

        config([
            'services.auth.client_id' => $oauthClient->id,
            'services.auth.client_secret' => $oauthClient->secret_plaintext,
            'services.auth.scopes' => $clientApp->scopes ?: config('services.auth.scopes'),
        ]);

        return $next($request);
    }
}
