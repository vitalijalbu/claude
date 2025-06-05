<?php

declare(strict_types=1);

namespace App\Providers;

use Faker\Provider\Base;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;

class CustomProvider extends Base
{
    /**
     * Generate an ULID for faker.
     */
    public function ulid(): Ulid
    {
        return Str::ulid();
    }
}
