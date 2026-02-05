<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EducationMembersTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'Ahmet',
                'Yılmaz',
                'Mehmet',
                'Yılmaz',
                'ahmet@email.com',
                '5551234567',
                500,
                'active'
            ],
            [
                'Fatma',
                'Demir',
                'Ayşe',
                'Demir',
                '',
                '5559876543',
                400,
                'active'
            ],
            [
                'Mustafa',
                'Kaya',
                'Ali',
                'Kaya',
                'mustafa@email.com',
                '5555555555',
                600,
                'active'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'veli_adi',
            'veli_soyadi',
            'ogrenci_adi',
            'ogrenci_soyadi',
            'email',
            'telefon',
            'aylik_aidat',
            'durum'
        ];
    }

}
