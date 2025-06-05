<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\VisitReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;

final class CertificateController extends Controller
{
    /**
     * Store a new certificate for a visit.
     */
    public function store(Request $request, Visit $visit)
    {
        // Verifica che la visita abbia i dati necessari
        if (! $visit->report) {
            return response()->json([
                'error' => 'Nessun report trovato per questa visita',
            ], 404);
        }

        // Genera il PDF del certificato usando i dati della visita
        $pdf = Pdf::view('pdf.certificate', [
            'visit' => $visit,
            'supplier' => $visit->supplier,
            'report' => $visit->report,
            'organization' => $visit->organization,
        ]);

        // Definisce il percorso del file
        $fileName = "certificati/certificato_visita_{$visit->id}.pdf";

        // Salva il PDF nello storage
        Storage::disk('public')->put($fileName, $pdf->output());

        // Aggiorna la visita con il percorso del certificato
        $visit->update([
            'certificate_path' => $fileName,
        ]);

        return response()->json([
            'message' => 'Certificato salvato con successo',
            'visit' => $visit,
            'certificate_url' => Storage::disk('public')->url($fileName),
        ]);
    }

    /**
     * Display the specified certificate.
     */
    public function show(Visit $visit)
    {
        $report = $visit->report;

        if (! $report) {
            return response()->json([
                'error' => 'Nessun report trovato per questa visita',
            ], 404);
        }

        // Se il certificato esiste già nello storage, restituisce l'URL
        if ($visit->certificate_path && Storage::disk('public')->exists($visit->certificate_path)) {
            return response()->json([
                'certificate_url' => Storage::disk('public')->url($visit->certificate_path),
                'visit' => $visit,
            ]);
        }

        // Altrimenti genera il PDF al volo e lo salva
        $pdf = Pdf::view('pdf.certificate', [
            'visit' => $visit,
            'supplier' => $visit->supplier,
            'report' => $report,
            'organization' => $visit->organization,
        ]);

        $fileName = "certificati/certificato_visita_{$visit->id}.pdf";
        Storage::disk('public')->put($fileName, $pdf->output());

        // Aggiorna il percorso nel database
        $visit->update(['certificate_path' => $fileName]);

        return response()->json([
            'certificate_url' => Storage::disk('public')->url($fileName),
            'visit' => $visit,
        ]);
    }

    /**
     * Update the specified certificate.
     */
    public function update(Request $request, Visit $visit, $idCertificate)
    {
        // Verifica che la visita abbia i dati necessari
        if (! $visit->report) {
            return response()->json([
                'error' => 'Nessun report trovato per questa visita',
            ], 404);
        }

        // Rimuove il vecchio certificato se esiste
        if ($visit->certificate_path && Storage::disk('public')->exists($visit->certificate_path)) {
            Storage::disk('public')->delete($visit->certificate_path);
        }

        // Genera il nuovo PDF usando i dati aggiornati della visita
        $pdf = Pdf::view('pdf.certificate', [
            'visit' => $visit,
            'supplier' => $visit->supplier,
            'report' => $visit->report,
            'organization' => $visit->organization,
        ]);

        $fileName = "certificati/certificato_visita_{$visit->id}.pdf";
        Storage::disk('public')->put($fileName, $pdf->output());

        $visit->update([
            'certificate_path' => $fileName,
        ]);

        return response()->json([
            'message' => 'Certificato aggiornato con successo',
            'visit' => $visit,
            'certificate_url' => Storage::disk('public')->url($fileName),
        ]);
    }

    /**
     * Generate a report certificate.
     */
    public function generateReportCertificate(VisitReport $report)
    {
        $visit = $report->visit;
        $supplier = $visit ? $visit->supplier : $report->productionTest?->supplier;

        if (! $supplier) {
            return response()->json([
                'error' => 'Impossibile determinare il fornitore per questo report',
            ], 404);
        }

        // Controlla se il certificato esiste già
        $fileName = "certificati/certificato_report_{$report->id}.pdf";

        if (! Storage::disk('public')->exists($fileName)) {
            // Genera e salva il PDF
            $pdf = Pdf::view('pdf.report-certificate', [
                'report' => $report,
                'visit' => $visit,
                'supplier' => $supplier,
                'organization' => $report->organization,
            ]);

            Storage::disk('public')->put($fileName, $pdf->output());

            // Aggiorna il report con il percorso del certificato
            $report->update(['certificate_path' => $fileName]);
        }

        return response()->json([
            'certificate_url' => Storage::disk('public')->url($fileName),
            'report' => $report,
        ]);
    }
}
