<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Atlas\Enums\RoleEnum;
use App\Models\Role;
use Illuminate\Foundation\Queue\Queueable;

class InitRoles
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Role::query()->upsert(
            collect(RoleEnum::cases())->map(
                fn (RoleEnum $role) => [
                    'guard_name' => 'atlas',
                    'name' => $role->value,
                ]
            )->toArray(),
            ['guard_name', 'name'],
            ['created_at', 'updated_at']
        );
    }
}
