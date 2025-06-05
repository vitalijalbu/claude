<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Visit;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VisitNewReportRule implements ValidationRule
{
    public function __construct(private Visit $visit) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->visit->report) {
            $fail(__('This visit already has a report.'));
        }
    }
}
