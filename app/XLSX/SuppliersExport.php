<?php

declare(strict_types=1);

namespace App\XLSX;

use Illuminate\Support\Collection;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SuppliersExport
{
    private Collection $suppliers;

    public function __construct($suppliers)
    {
        $this->suppliers = $suppliers instanceof Collection ? $suppliers : collect($suppliers);
    }

    public function download(string $fileName): StreamedResponse
    {
        return SimpleExcelWriter::streamDownload($fileName)
            ->addHeader([
                'ID',
                'Nome',
                'Email',
                'Telefono',
                'P.IVA',
                'Organizzazione',
                'Città',
                'Provincia',
                'Status',
                'Priorità',
                'Score',
                'Ultima Visita',
                'Data Creazione',
            ])
            ->addRows($this->suppliers->map(function ($supplier) {
                return [
                    $supplier->id,
                    $supplier->name,
                    $supplier->email,
                    $supplier->phone,
                    $supplier->vat,
                    $supplier->organization->name ?? 'N/A',
                    $supplier->city,
                    $supplier->province,
                    $supplier->status->value ?? 'N/A',
                    $supplier->priority->value ?? 'N/A',
                    $supplier->pre_assessment_score->value ?? 'N/A',
                    $supplier->lastVisit->created_at->format('d/m/Y') ?? '-',
                    $supplier->created_at->format('d/m/Y H:i'),
                ];
            })->toArray())
            ->toBrowser();
    }
}
