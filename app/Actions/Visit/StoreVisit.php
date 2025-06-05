<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\Atlas\Enums\RoleEnum;
use App\DTO\Visit\StoreVisitDto;
use App\Enums\VisitStatus;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreVisit
{
    public function execute(array $requestData): Collection
    {
        return DB::transaction(function () use ($requestData) {
            $visits = collect();
            $supplierIds = $requestData['supplier_ids'];

            $baseVisitData = collect($requestData)->except('supplier_ids')->toArray();

            foreach ($supplierIds as $index => $supplierId) {
                try {
                    $visitData = array_merge($baseVisitData, [
                        'supplier_id' => $supplierId,
                    ]);

                    $dto = StoreVisitDto::from($visitData);

                    $inspector = $this->createOrUpdateInspector($dto);

                    $alreadyToPlan = Visit::where('supplier_id', $dto->supplier_id)
                        ->where('status', VisitStatus::PLANNED->value)
                        ->exists();

                    if ($alreadyToPlan) {
                        throw ValidationException::withMessages([
                            "supplier_ids.{$index}" => 'One of the suppliers already has a planned visit.',
                        ]);
                    }

                    $visitCreateData = collect($dto->toArray())
                        ->except(['inspector_name', 'inspector_email', 'inspector_atlas_id'])
                        ->merge(['inspector_id' => $inspector->id])
                        ->toArray();

                    $visit = Visit::create([
                        ...$visitCreateData,
                        'status' => VisitStatus::PLANNED->value,
                        'form_id' => 1,
                    ]);

                    $visits->push($visit);
                } catch (ValidationException $e) {
                    throw $e;
                }
            }

            return $visits;
        });
    }

    private function createOrUpdateInspector(StoreVisitDto $dto): User
    {
        $user = User::query()->updateOrCreate(
            ['atlas_member_id' => $dto->inspector_atlas_id],
            [
                'name' => $dto->inspector_name,
                'email' => $dto->inspector_email,
            ]
        );

        $user->syncRoles(RoleEnum::TECH_USER);

        return $user;
    }
}
