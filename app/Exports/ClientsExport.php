<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ClientsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithTitle
{
    public function __construct(private readonly array $filters = []) {}

    public function query(): Builder
    {
        $query = Client::with('primaryContact')->withCount('contacts');

        if (!empty($this->filters['search'])) {
            $query->search($this->filters['search']);
        }

        if (!empty($this->filters['status'])) {
            $query->byStatus($this->filters['status']);
        }

        return $query->latest();
    }

    public function title(): string
    {
        return 'Clientes';
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Empresa', 'Email', 'Teléfono', 'Estado', 'Contactos', 'Contacto Principal', 'Registrado'];
    }

    public function map($client): array
    {
        return [
            $client->id,
            $client->name,
            $client->company ?? '',
            $client->email   ?? '',
            $client->phone   ?? '',
            ucfirst($client->status),
            $client->contacts_count,
            $client->primaryContact?->name ?? '—',
            $client->created_at->format('d/m/Y'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'fill' => [
                        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF4F46E5'],
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(22);
            },
        ];
    }
}
