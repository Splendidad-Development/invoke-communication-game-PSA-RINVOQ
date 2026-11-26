<?php

namespace App\Http\Controllers;

use App\PlayerExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Every player with their answers: shown on /export and downloadable as PDF or Excel.
 */
class ExportController extends Controller
{
    public function index(PlayerExport $export): Response
    {
        return Inertia::render('export/Index', [
            'title' => $export->title(),
            'players' => $export->players(),
            'totalSlots' => $export->totalSlots(),
        ]);
    }

    public function pdf(PlayerExport $export): HttpResponse
    {
        return Pdf::loadView('exports.players', [
            'title' => $export->title(),
            'players' => $export->players(),
            'totalSlots' => $export->totalSlots(),
            'generatedAt' => now()->format('Y-m-d H:i'),
        ])
            ->setPaper('a4', 'landscape')
            // Embed only the glyphs used, so the file stays small.
            ->setOption('isFontSubsettingEnabled', true)
            ->download($export->fileName('pdf'));
    }

    public function excel(PlayerExport $export): BinaryFileResponse
    {
        $path = tempnam(sys_get_temp_dir(), 'export');
        $export->writeXlsx($export->players(), $path);

        return response()
            ->download($path, $export->fileName('xlsx'), [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])
            ->deleteFileAfterSend();
    }
}
