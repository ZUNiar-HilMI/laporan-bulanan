<?php

namespace App\Exports;

use App\Models\Kegiatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Collection;

class KegiatanExport implements FromCollection, WithHeadings, WithDrawings, WithStyles, WithColumnWidths, WithCustomStartCell
{
    protected $kegiatan;
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->kegiatan = Kegiatan::with('user')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->where('status', 'approved')
            ->get();
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function collection()
    {
        $data = new Collection();
        
        foreach ($this->kegiatan as $index => $item) {
            $data->push([
                'no' => $index + 1,
                'nama_kegiatan' => $item->nama_kegiatan,
                'deskripsi' => $item->deskripsi,
                'anggaran' => 'Rp ' . number_format($item->anggaran, 0, ',', '.'),
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'pelapor' => $item->user->name ?? '-',
                'tanggal' => $item->created_at->format('d/m/Y'),
                'foto_sebelum' => '', // Placeholder for image
                'foto_sesudah' => '', // Placeholder for image
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kegiatan',
            'Deskripsi',
            'Anggaran',
            'Latitude',
            'Longitude',
            'Pelapor',
            'Tanggal',
            'Foto Sebelum',
            'Foto Sesudah',
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2; // Start from row 2 (after header)

        foreach ($this->kegiatan as $item) {
            // Foto Sebelum
            if ($item->foto_sebelum && file_exists(storage_path('app/public/' . $item->foto_sebelum))) {
                $drawing = new Drawing();
                $drawing->setName('Foto Sebelum');
                $drawing->setDescription('Foto Sebelum');
                $drawing->setPath(storage_path('app/public/' . $item->foto_sebelum));
                $drawing->setHeight(80);
                $drawing->setWidth(100);
                $drawing->setCoordinates('I' . $row);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);
                $drawings[] = $drawing;
            }

            // Foto Sesudah
            if ($item->foto_sesudah && file_exists(storage_path('app/public/' . $item->foto_sesudah))) {
                $drawing = new Drawing();
                $drawing->setName('Foto Sesudah');
                $drawing->setDescription('Foto Sesudah');
                $drawing->setPath(storage_path('app/public/' . $item->foto_sesudah));
                $drawing->setHeight(80);
                $drawing->setWidth(100);
                $drawing->setCoordinates('J' . $row);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);
                $drawings[] = $drawing;
            }

            $row++;
        }

        return $drawings;
    }

    public function styles(Worksheet $sheet)
    {
        // Style header row
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4B5320'], // Army green
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set row height for data rows (to fit images)
        $totalRows = $this->kegiatan->count();
        for ($i = 2; $i <= $totalRows + 1; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(85);
        }

        // Style all cells
        $sheet->getStyle('A1:J' . ($totalRows + 1))->applyFromArray([
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 25,  // Nama Kegiatan
            'C' => 35,  // Deskripsi
            'D' => 18,  // Anggaran
            'E' => 12,  // Latitude
            'F' => 12,  // Longitude
            'G' => 15,  // Pelapor
            'H' => 12,  // Tanggal
            'I' => 18,  // Foto Sebelum
            'J' => 18,  // Foto Sesudah
        ];
    }
}
