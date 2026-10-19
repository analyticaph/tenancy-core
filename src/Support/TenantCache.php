<?php

namespace Analyticaph\TenancyCore\Support;

use Analyticaph\TenancyCore\Models\OAuthClient;
use Analyticaph\TenancyCore\Models\OAuthClientApp;
use Analyticaph\TenancyCore\Models\Tenant;
use Analyticaph\TenancyCore\Models\TenantDomain;
use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Cached landlord lookups that run on every request (tenant by domain / id,
 * the tenant's OAuth client credentials).
 *
 * Raw attribute arrays are cached rather than serialized models, so
 * SharedEncrypted columns (database_password, secret_plaintext) stay
 * ciphertext in the cache and no object unserialization is involved.
 *
 * Tenants and OAuth clients are edited from the console app, which can't
 * reach this app's cache prefix, so invalidation is TTL-only: changes take up
 * to `tenancy-core.cache.ttl` seconds to apply. A TTL of 0 bypasses the cache.
 * Misses are never cached.
 */
class TenantCache
{
    public static function forDomain(string $host): ?Tenant
    {
        $attributes = self::remember('tenancy-core:domain:'.strtolower($host), function () use ($host) {
            return TenantDomain::where('domain', $host)->with('tenant')->first()?->tenant?->getAttributes();
        });

        return $attributes ? (new Tenant)->newFromBuilder($attributes) : null;
    }

    public static function find(string $id): ?Tenant
    {
        $attributes = self::remember("tenancy-core:tenant:{$id}", fn () => Tenant::find($id)?->getAttributes());

        return $attributes ? (new Tenant)->newFromBuilder($attributes) : null;
    }

    /**
     * The tenant's confidential OAuth client and its active app row for
     * $appSlug. Either may be null; only a complete pair is cached.
     *
     * @return array{client: OAuthClient|null, app: OAuthClientApp|null}
     */
    public static function oauthCredentials(Tenant $tenant, string $appSlug): array
    {
        $cached = self::remember(
            "tenancy-core:oauth:{$tenant->getKey()}:{$appSlug}",
            function () use ($tenant, $appSlug) {
                $client = $tenant->oauthClient;

                $app = $client?->apps()
                    ->whereHas('app', fn ($query) => $query->where('slug', $appSlug))
                    ->where('is_active', true)
                    ->first();

                return ['client' => $client?->getAttributes(), 'app' => $app?->getAttributes()];
            },
            fn (array $value) => $value['client'] !== null && $value['app'] !== null,
        );

        return [
            'client' => $cached['client'] ? (new OAuthClient)->newFromBuilder($cached['client']) : null,
            'app' => $cached['app'] ? (new OAuthClientApp)->newFromBuilder($cached['app']) : null,
        ];
    }

    /**
     * @param  Closure(): (array<string, mixed>|null)  $resolve
     * @param  (Closure(array<string, mixed>): bool)|null  $cacheable  Defaults to "not null".
     * @return array<string, mixed>|null
     */
    private static function remember(string $key, Closure $resolve, ?Closure $cacheable = null): ?array
    {
        $ttl = (int) config('tenancy-core.cache.ttl', 300);

        if ($ttl <= 0) {
            return $resolve();
        }

        $cached = Cache::get($key);

        if (is_array($cached)) {
            return $cached;
        }

        $value = $resolve();

        if ($value !== null && ($cacheable === null || $cacheable($value))) {
            Cache::put($key, $value, $ttl);
        }

        return $value;
    }
}
