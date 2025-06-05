<?php

declare(strict_types=1);

namespace App\Atlas\Services;

use App\Atlas\DTOs\TokenPayload;
use App\Atlas\Exceptions\InvalidTokenAtlasException;
use App\Models\Organization;
use Illuminate\Support\Arr;

class OrganizationService
{
    public function resolveOrganizationFromToken(TokenPayload $tokenPayload): ?Organization
    {
        if (empty($tokenPayload->organizationId)) {
            throw new InvalidTokenAtlasException('Atlas token payload does not contain a organizationId. Cannot resolve organization.');
        }

        $organization = Organization::where('atlas_organization_id', $tokenPayload->organizationId)->first();

        return $organization;
    }

    public function createOrganization(array $data): Organization
    {
        $organization = Organization::create([
            'name' => Arr::get($data, 'name'),
            'atlas_organization_id' => Arr::get($data, 'atlas_organization_id'),
        ]);

        return $organization;
    }
}
