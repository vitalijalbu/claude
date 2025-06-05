<?php

declare(strict_types=1);

namespace App\Atlas\DTOs;

use DateTimeImmutable;
use InvalidArgumentException;

class TokenPayload
{
    public readonly string $iss;

    public readonly string|array $aud;

    public readonly string $sub;

    public readonly DateTimeImmutable $iat;

    public readonly DateTimeImmutable $exp;

    public readonly ?string $jti;

    public readonly ?string $clientId;

    public readonly ?string $organizationId;

    public readonly array $roles;

    /**
     * Costruttore che prende un array associativo (il payload JWT decodificato)
     * e popola le proprietà del DTO.
     */
    public function __construct(array $payload)
    {
        // Validazione minima per i claim obbligatori che ti aspetti
        if (! isset($payload['iss'], $payload['aud'], $payload['sub'], $payload['iat'], $payload['exp'])) {
            throw new InvalidArgumentException('Missing essential Atlas Token Payload claims.');
        }

        $this->iss = $payload['iss'];
        $this->aud = $payload['aud'];
        $this->sub = $payload['sub'];
        $this->iat = (new DateTimeImmutable())->setTimestamp($payload['iat']);
        $this->exp = (new DateTimeImmutable())->setTimestamp($payload['exp']);
        $this->jti = $payload['jti'] ?? null;

        $this->organizationId = $payload['app_metadata']->organization ?? null;
        $this->roles = (array) ($payload['app_metadata']->roles ?? []);
        $this->clientId = $payload['client_id'] ?? null;
    }

    /**
     * Getter per l'ID utente (alias di 'sub').
     */
    public function getUserId(): string
    {
        return $this->sub;
    }

    /**
     * Verifica se il token è scaduto.
     */
    public function isExpired(): bool
    {
        return $this->exp < new DateTimeImmutable();
    }

    /**
     * Verifica se l'utente ha un determinato ruolo.
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }
}
