<?php

namespace Analyticaph\TenancyCore\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Config;
use RuntimeException;

/**
 * Like the built-in 'encrypted' cast, but uses TENANT_DB_KEY instead of APP_KEY.
 * This allows cross-app decryption of shared sensitive fields (e.g. tenant DB credentials)
 * without requiring all apps to share the same APP_KEY.
 */
class SharedEncrypted implements CastsAttributes
{
    private function encrypter(): Encrypter
    {
        $key = Config::get('tenancy-core.shared_encryption_key')
            ?? throw new RuntimeException('TENANT_DB_KEY is not set. All apps that access tenant database credentials must define this environment variable.');

        $raw = str_starts_with($key, 'base64:') ? base64_decode(substr($key, 7)) : $key;

        return new Encrypter($raw, 'AES-256-CBC');
    }

    public function get($model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        return $this->encrypter()->decrypt($value);
    }

    public function set($model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        return $this->encrypter()->encrypt($value);
    }
}
