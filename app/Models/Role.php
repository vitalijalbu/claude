<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Role as ModelsRole;
use App\Atlas\Enums\RoleEnum;

class Role extends ModelsRole
{
    use HasUuids;

    protected function casts()
    {
        return [
            'name' => RoleEnum::class
        ];
    }
}
