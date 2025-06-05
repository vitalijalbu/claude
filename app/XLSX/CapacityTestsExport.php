<?php

declare(strict_types=1);

namespace App\XLSX;

use Illuminate\Support\Collection;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CapacityTestsExport
{
    private Collection $capacityTests;

    public function __construct($capacityTests)
    {
        $this->capacityTests = $capacityTests instanceof Collection ? $capacityTests : collect($capacityTests);
    }

    public function download(string $fileName): StreamedResponse
    {
        return SimpleExcelWriter::streamDownload($fileName)
            ->addHeader([
                'ID',
                'Fornitore',
                'Creato da',
                'Data Test',
                'Risultato',
                'Status',
                'Scadenza Test',
                'Status Test',
                'Tipo Prodotto',
                'Invia Prodotto',
                'Data Creazione',
                'Data Aggiornamento',
            ])
            ->addRows($this->capacityTests->map(function ($test) {
                return [
                    $test->id,
                    $test->supplier->name ?? 'N/A',
                    $test->creator->name ?? 'N/A',
                    $test->test_date->format('d/m/Y') ?? '-',
                    $test->result->value ?? 'N/A',
                    $test->status->value ?? 'N/A',
                    $test->test_deadline->format('d/m/Y') ?? '-',
                    $test->test_status->value ?? 'N/A',
                    $test->product_type ?? 'N/A',
                    $test->send_product ? 'Sì' : 'No',
                    $test->created_at->format('d/m/Y H:i'),
                    $test->updated_at->format('d/m/Y H:i'),
                ];
            })->toArray())
            ->toBrowser();
    }
}
