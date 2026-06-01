<?php

namespace App\Http\Controllers;

use App\Contracts\Services\ClientServiceInterface;
use App\Exports\ClientsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ExportController extends Controller
{
    public function __construct(
        private readonly ClientServiceInterface $clientService
    ) {}

    public function excel(Request $request): BinaryFileResponse
    {
        $filters  = $request->only(['search', 'status']);
        $filename = 'clientes_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new ClientsExport($filters), $filename);
    }

    public function pdf(Request $request): Response
    {
        $filters  = $request->only(['search', 'status']);
        $clients  = $this->clientService->getForExport($filters);
        $filename = 'clientes_' . now()->format('Y-m-d') . '.pdf';

        return Pdf::loadView('exports.clients-pdf', [
            'clients'     => $clients,
            'filters'     => $filters,
            'generatedAt' => now(),
        ])
        ->setPaper('A4', 'landscape')
        ->download($filename);
    }
}
