<?php

namespace App\Exports;

use App\Models\EducationMember;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EducationMembersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    protected $members;
    protected $year;

    public function __construct($members, $year = null)
    {
        $this->members = $members;
        $this->year = $year ?: now()->year;
    }

    public function collection()
    {
        return $this->members;
    }

    public function headings(): array
    {
        return [
            'Veli Adı',
            'Öğrenci Adı',
            'Öğrenci Soyadı',
            'E-posta',
            'Telefon',
            'Aylık Aidat (€)',
            'Durum',
            'Üyelik Tarihi',
            'Toplam Aidat (' . $this->year . ')',
            'Ödenmiş (' . $this->year . ')',
            'Ödenmemiş (' . $this->year . ')',
            'Toplam Ödenen (€)',
            'Toplam Bekleyen (€)',
            'Ödenmemiş Aylar'
        ];
    }

    public function map($member): array
    {
        // Filter dues by year
        $yearDues = $member->dues->filter(function($due) {
            return (int) $due->due_date->year === (int) $this->year;
        });

        $totalDues = $yearDues->count();
        $paidDues = $yearDues->where('status', 'paid')->count();
        $unpaidDues = $yearDues->where('status', '!=', 'paid')->count();
        
        $totalPaidAmount = $yearDues->where('status', 'paid')->sum('amount');
        $totalPendingAmount = $yearDues->where('status', '!=', 'paid')->sum('amount');

        // Get unpaid months as numbers - sorted by month number (1-12)
        $unpaidMonths = $yearDues->where('status', '!=', 'paid')
            ->map(function($due) {
                return $due->due_date->month; // Get month number
            })
            ->unique()
            ->sort() // Sort by month number (1-12)
            ->values()
            ->implode(', ');

        return [
            $member->name,
            $member->student_name,
            $member->student_surname,
            $member->email,
            $member->phone,
            number_format($member->monthly_dues, 2),
            $member->status == 'active' ? 'Aktif' : ($member->status == 'inactive' ? 'Pasif' : 'Askıda'),
            $member->membership_date->format('d.m.Y'),
            $totalDues,
            $paidDues,
            $unpaidDues,
            number_format($totalPaidAmount, 2),
            number_format($totalPendingAmount, 2),
            $unpaidMonths ?: '-'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // Veli Adı
            'B' => 15, // Öğrenci Adı
            'C' => 15, // Öğrenci Soyadı
            'D' => 25, // E-posta
            'E' => 15, // Telefon
            'F' => 15, // Aylık Aidat
            'G' => 10, // Durum
            'H' => 15, // Üyelik Tarihi
            'I' => 12, // Toplam Aidat
            'J' => 10, // Ödenmiş
            'K' => 10, // Ödenmemiş
            'L' => 18, // Toplam Ödenen
            'M' => 18, // Toplam Bekleyen
            'N' => 20, // Ödenmemiş Aylar
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'], // Indigo color
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Add borders to all cells
                $sheet->getStyle('A1:N' . ($this->members->count() + 1))
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Auto-filter on header row
                $sheet->setAutoFilter('A1:N1');

                // Freeze header row
                $sheet->freezePane('A2');

                // Add summary row at the bottom
                $lastRow = $this->members->count() + 2;
                $sheet->setCellValue('A' . $lastRow, 'TOPLAM');
                $sheet->setCellValue('I' . $lastRow, '=SUM(I2:I' . ($lastRow - 1) . ')');
                $sheet->setCellValue('J' . $lastRow, '=SUM(J2:J' . ($lastRow - 1) . ')');
                $sheet->setCellValue('K' . $lastRow, '=SUM(K2:K' . ($lastRow - 1) . ')');
                $sheet->setCellValue('L' . $lastRow, '=SUM(L2:L' . ($lastRow - 1) . ')');
                $sheet->setCellValue('M' . $lastRow, '=SUM(M2:M' . ($lastRow - 1) . ')');

                // Style summary row
                $sheet->getStyle('A' . $lastRow . ':N' . $lastRow)
                    ->getFont()
                    ->setBold(true);
                $sheet->getStyle('A' . $lastRow . ':N' . $lastRow)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('F3F4F6'); // Light gray background

                // Center align numeric columns
                $sheet->getStyle('F2:M' . $lastRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Center align unpaid months column (numbers)
                $sheet->getStyle('N2:N' . $lastRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Format currency columns
                $sheet->getStyle('F2:F' . $lastRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00" €"');
                $sheet->getStyle('L2:M' . $lastRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00" €"');
            },
        ];
    }
}



