<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Supplier;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SuppliersNewVisitRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $suppliers = Supplier::query()
            ->whereIn('id', $value)
            ->with(['lastVisit'])
            ->get();

        if ($suppliers->count() !== count($value)) {
            $fail(__('One or more suppliers do not exist.'));

            return;
        }

        $suppliers->each(function (Supplier $supplier) use ($fail) {
            if ($supplier->lastVisit) {
                $fail(__('Supplier :supplier already has a visit planned for :date', [
                    'supplier' => $supplier->name,
                    'date' => $supplier->lastVisit->date->format('Y-m-d'),
                ]));
            }
        });
    }
}
