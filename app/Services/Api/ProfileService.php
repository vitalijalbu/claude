<?php

namespace App\Services\Api;

use App\Models\Profile;
use App\Repositories\ProfileRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProfileService
{
    protected ProfileRepository $repository;

    public function __construct(ProfileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAll($params): LengthAwarePaginator
    {
        return $this->repository->findAll($params);
    }

    public function findByPhone(string $phone_number): Profile
    {
        return $this->repository->findByPhone($phone_number);
    }

    public function updateOrCreate(array $data): Profile
    {
        if (empty($data['phone_number'])) {
            throw new \Exception("Missing required fields: 'phone_number'");
        }

        // Usa direttamente updateOrCreate nel repository
        return $this->repository->updateOrCreate(
            [
                'phone_number' => $data['phone_number'],
            ],
            $data
        );
    }
}
