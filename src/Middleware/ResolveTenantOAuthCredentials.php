<?php

namespace Analyticaph\TenancyCore\Middleware;

use Analyticaph\TenancyCore\Exceptions\AppNotProvisionedException;
use Analyticaph\TenancyCore\Support\TenantCache;
use Analyticaph\TenancyCore\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantOAuthCredentials
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = TenantContext::currentOrFail();

        $appSlug = (string) config('oauth-client.app_slug');

        ['client' => $oauthClient, 'app' => $clientApp] = TenantCache::oauthCredentials($tenant, $appSlug);

        if (! $oauthClient) {
            throw new AppNotProvisionedException(
                "Tenant {$tenant->getKey()} has no OAuth client provisioned."
            );
        }

        if (! $clientApp) {
            throw new AppNotProvisionedException(
                "Tenant {$tenant->getKey()} is not provisioned for app '{$appSlug}'."
            );
        }

        config([
            'services.auth.client_id' => $oauthClient->id,
            'services.auth.client_secret' => $oauthClient->secret_plaintext,
            'services.auth.scopes' => $clientApp->scopes ?: config('services.auth.scopes'),
        ]);

        return $next($request);
    }
}
