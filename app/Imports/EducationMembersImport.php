<?php

namespace App\Imports;

use App\Models\EducationMember;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Maatwebsite\Excel\Validators\Failure;

class EducationMembersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading
{
    use Importable;

    private $importedCount = 0;
    private $skippedCount = 0;
    private $errors = [];

    public function model(array $row)
    {
        // Excel'den gelen verileri temizle
        $name = trim($row['veli_adi'] ?? '');
        $surname = trim($row['veli_soyadi'] ?? '');
        $studentName = trim($row['ogrenci_adi'] ?? '');
        $studentSurname = trim($row['ogrenci_soyadi'] ?? '');
        $email = trim($row['email'] ?? '');
        $phone = trim(strval($row['telefon'] ?? ''));
        $monthlyDues = floatval($row['aylik_aidat'] ?? 0);
        $status = trim($row['durum'] ?? 'active');

        // Boş satırları atla
        if (empty($name) && empty($surname) && empty($studentName) && empty($studentSurname)) {
            return null;
        }

        // Tekrar kontrolü yap
        $existingMember = EducationMember::where('name', $name)
            ->where('surname', $surname)
            ->where('student_name', $studentName)
            ->where('student_surname', $studentSurname)
            ->first();

        if ($existingMember) {
            $this->skippedCount++;
            $this->errors[] = "Satır atlandı: {$name} {$surname} - {$studentName} {$studentSurname} (Zaten mevcut)";
            return null;
        }

        // Durum kontrolü
        if (!in_array($status, ['active', 'inactive'])) {
            $status = 'active';
        }

        // Telefon numarasını temizle (sadece rakamlar)
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // E-posta kontrolü (boş olabilir)
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = null; // Geçersiz e-posta adresini null yap
        }

        $this->importedCount++;

        $member = new EducationMember([
            'name' => $name,
            'surname' => $surname,
            'student_name' => $studentName,
            'student_surname' => $studentSurname,
            'email' => $email,
            'phone' => $phone,
            'status' => $status,
            'membership_date' => Carbon::now(),
            'monthly_dues' => $monthlyDues,
            'notes' => 'Excel ile içe aktarıldı',
        ]);

        return $member;
    }

    public function rules(): array
    {
        return [
            'veli_adi' => 'required|string|max:255',
            'veli_soyadi' => 'required|string|max:255',
            'ogrenci_adi' => 'required|string|max:255',
            'ogrenci_soyadi' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefon' => 'nullable|max:20',
            'aylik_aidat' => 'nullable|numeric|min:0',
            'durum' => 'nullable|string|in:active,inactive',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'veli_adi.required' => 'Veli adı gereklidir.',
            'veli_soyadi.required' => 'Veli soyadı gereklidir.',
            'ogrenci_adi.required' => 'Öğrenci adı gereklidir.',
            'ogrenci_soyadi.required' => 'Öğrenci soyadı gereklidir.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'aylik_aidat.numeric' => 'Aylık aidat sayısal olmalıdır.',
            'durum.in' => 'Durum active veya inactive olmalıdır.',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getSkippedCount()
    {
        return $this->skippedCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Handle errors during import
     */
    public function onError(\Throwable $error)
    {
        $this->errors[] = 'Hata: ' . $error->getMessage();
        Log::error('Education Members Import Error: ' . $error->getMessage());
    }

    /**
     * Handle validation failures during import
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = "Satır {$failure->row()}: " . implode(', ', $failure->errors());
        }
    }
}
