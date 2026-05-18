<?php

namespace TenancyCore\Passport;

use Laravel\Passport\Bridge\ClientRepository as PassportBridgeClientRepository;

class EncryptedSecretClientRepository extends PassportBridgeClientRepository
{
    public function validateClient(string $clientIdentifier, ?string $clientSecret, ?string $grantType): bool
    {
        $record = $this->clients->findActive($clientIdentifier);

        if ($record === null || empty($clientSecret)) {
            return false;
        }

        return hash_equals((string) $record->secret, $clientSecret);
    }
}
