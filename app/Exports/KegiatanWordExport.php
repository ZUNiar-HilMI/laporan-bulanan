<?php

namespace App\Exports;

use App\Models\Kegiatan;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use Illuminate\Support\Facades\Response;

class KegiatanWordExport
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

    public function download()
    {
        $phpWord = new PhpWord();

        // Set default font
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        // Add section
        $section = $phpWord->addSection([
            'orientation' => 'landscape',
            'marginLeft' => 720,
            'marginRight' => 720,
            'marginTop' => 720,
            'marginBottom' => 720,
        ]);

        // Title
        $section->addText(
            'LAPORAN KEGIATAN BULANAN',
            ['bold' => true, 'size' => 16, 'color' => '1e1b4b'],
            ['alignment' => 'center', 'spaceAfter' => 120]
        );

        // Subtitle with month/year
        $namaBulan = $this->getNamaBulan($this->bulan);
        $section->addText(
            "Periode: {$namaBulan} {$this->tahun}",
            ['size' => 12, 'color' => '666666'],
            ['alignment' => 'center', 'spaceAfter' => 300]
        );

        // Summary info
        $totalKegiatan = $this->kegiatan->count();
        $totalAnggaran = $this->kegiatan->sum('anggaran');
        $section->addText(
            "Total Kegiatan: {$totalKegiatan} | Total Anggaran: Rp " . number_format($totalAnggaran, 0, ',', '.'),
            ['size' => 11, 'bold' => true],
            ['alignment' => 'center', 'spaceAfter' => 300]
        );

        if ($this->kegiatan->count() > 0) {
            // Create table
            $tableStyle = [
                'borderSize' => 6,
                'borderColor' => '999999',
                'cellMargin' => 80,
            ];
            $phpWord->addTableStyle('KegiatanTable', $tableStyle);
            $table = $section->addTable('KegiatanTable');

            // Header style
            $headerStyle = ['bgColor' => '1e1b4b', 'valign' => 'center'];
            $headerFontStyle = ['bold' => true, 'color' => 'FFFFFF', 'size' => 10];
            $cellStyle = ['valign' => 'center'];
            $textStyle = ['size' => 9];

            // Header row
            $table->addRow(400);
            $table->addCell(500, $headerStyle)->addText('No', $headerFontStyle, ['alignment' => 'center']);
            $table->addCell(2000, $headerStyle)->addText('Nama Kegiatan', $headerFontStyle, ['alignment' => 'center']);
            $table->addCell(2500, $headerStyle)->addText('Deskripsi', $headerFontStyle, ['alignment' => 'center']);
            $table->addCell(1500, $headerStyle)->addText('Anggaran', $headerFontStyle, ['alignment' => 'center']);
            $table->addCell(1200, $headerStyle)->addText('Lokasi', $headerFontStyle, ['alignment' => 'center']);
            $table->addCell(1200, $headerStyle)->addText('Pelapor', $headerFontStyle, ['alignment' => 'center']);
            $table->addCell(1000, $headerStyle)->addText('Tanggal', $headerFontStyle, ['alignment' => 'center']);
            $table->addCell(2000, $headerStyle)->addText('Foto Sebelum', $headerFontStyle, ['alignment' => 'center']);
            $table->addCell(2000, $headerStyle)->addText('Foto Sesudah', $headerFontStyle, ['alignment' => 'center']);

            // Data rows
            foreach ($this->kegiatan as $index => $item) {
                $rowBg = $index % 2 == 0 ? 'FFFFFF' : 'f8fafc';
                $rowStyle = ['bgColor' => $rowBg, 'valign' => 'center'];

                $table->addRow(1500); // Set minimum row height for images

                // No
                $table->addCell(500, $rowStyle)->addText($index + 1, $textStyle, ['alignment' => 'center']);

                // Nama Kegiatan
                $table->addCell(2000, $rowStyle)->addText($item->nama_kegiatan, $textStyle);

                // Deskripsi
                $table->addCell(2500, $rowStyle)->addText(
                    \Illuminate\Support\Str::limit($item->deskripsi, 80),
                    $textStyle
                );

                // Anggaran
                $table->addCell(1500, $rowStyle)->addText(
                    'Rp ' . number_format($item->anggaran, 0, ',', '.'),
                    $textStyle,
                    ['alignment' => 'right']
                );

                // Lokasi
                $table->addCell(1200, $rowStyle)->addText(
                    number_format($item->latitude, 4) . ', ' . number_format($item->longitude, 4),
                    ['size' => 8],
                    ['alignment' => 'center']
                );

                // Pelapor
                $table->addCell(1200, $rowStyle)->addText(
                    $item->user->name ?? '-',
                    $textStyle,
                    ['alignment' => 'center']
                );

                // Tanggal
                $table->addCell(1000, $rowStyle)->addText(
                    $item->created_at->format('d/m/Y'),
                    $textStyle,
                    ['alignment' => 'center']
                );

                // Foto Sebelum
                $fotoSebelumCell = $table->addCell(2000, $rowStyle);
                if ($item->foto_sebelum && file_exists(storage_path('app/public/' . $item->foto_sebelum))) {
                    $fotoSebelumCell->addImage(
                        storage_path('app/public/' . $item->foto_sebelum),
                        [
                            'width' => 100,
                            'height' => 75,
                            'alignment' => 'center',
                        ]
                    );
                } else {
                    $fotoSebelumCell->addText('-', $textStyle, ['alignment' => 'center']);
                }

                // Foto Sesudah
                $fotoSesudahCell = $table->addCell(2000, $rowStyle);
                if ($item->foto_sesudah && file_exists(storage_path('app/public/' . $item->foto_sesudah))) {
                    $fotoSesudahCell->addImage(
                        storage_path('app/public/' . $item->foto_sesudah),
                        [
                            'width' => 100,
                            'height' => 75,
                            'alignment' => 'center',
                        ]
                    );
                } else {
                    $fotoSesudahCell->addText('-', $textStyle, ['alignment' => 'center']);
                }
            }
        } else {
            $section->addText(
                'Tidak ada data kegiatan untuk periode ini.',
                ['italic' => true, 'color' => '666666'],
                ['alignment' => 'center', 'spaceBefore' => 300]
            );
        }

        // Footer
        $section->addTextBreak(2);
        $section->addText(
            'Dicetak pada: ' . now()->format('d F Y H:i'),
            ['size' => 9, 'color' => '999999'],
            ['alignment' => 'right']
        );

        // Save and download
        $filename = "laporan_kegiatan_{$this->bulan}_{$this->tahun}.docx";
        $tempFile = storage_path('app/public/' . $filename);

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    private function getNamaBulan($bulan)
    {
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        return $namaBulan[str_pad($bulan, 2, '0', STR_PAD_LEFT)] ?? $bulan;
    }
}
