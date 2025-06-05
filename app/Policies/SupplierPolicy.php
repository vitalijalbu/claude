<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        // Temporarily allow all actions for all users
        return true;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Supplier $supplier): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Supplier $supplier): bool
    {
        return true;
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        return true;
    }
}
