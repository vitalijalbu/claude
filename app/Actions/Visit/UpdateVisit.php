<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\Atlas\Enums\RoleEnum;
use App\Models\User;
use App\Models\Visit;

class UpdateVisit
{
    public function execute(Visit $visit, array $data): Visit
    {
        if ($data['inspector_atlas_id'] ?? false) {
            $inspector = $this->createOrUpdateInspector($data);

            $data['inspector_id'] = $inspector->id;
        }

        $visit->update(collect($data)->except([
            'inspector_name',
            'inspector_email',
            'inspector_atlas_id',
        ])->toArray());

        return $visit;
    }

    private function createOrUpdateInspector(array $data): User
    {
        $user = User::query()->updateOrCreate(
            ['atlas_member_id' => $data['inspector_atlas_id']],
            [
                'name' => $data['inspector_name'],
                'email' => $data['inspector_email'],
            ]
        );

        $user->syncRoles(RoleEnum::TECH_USER);

        return $user;
    }
}
