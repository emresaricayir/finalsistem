<?php

namespace App\Imports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MembersImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    private $currentMemberId = null;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip empty rows - only name and surname are required
        if (empty($row['ad']) || empty($row['soyad'])) {
            return null;
        }

        // Clean and normalize Turkish characters
        $name = $this->cleanTurkishText($row['ad']);
        $surname = $this->cleanTurkishText($row['soyad']);
        $birthPlace = $this->cleanTurkishText($row['dogum_yeri']);
        $nationality = $this->cleanTurkishText($row['uyruk'] ?? 'Türkiye');
        $occupation = $this->cleanTurkishText($row['meslek']);
        $address = $this->cleanTurkishText($row['adres']);
        $notes = !empty($row['notlar']) ? $this->cleanTurkishText($row['notlar']) : null;

        // Üye numarasını otomatik oluştur
        if ($this->currentMemberId === null) {
            $lastMember = Member::orderBy('id', 'desc')->first();
            $this->currentMemberId = $lastMember ? $lastMember->id : 0;
        }
        $this->currentMemberId++;
        $memberNo = 'Mitglied' . str_pad($this->currentMemberId, 3, '0', STR_PAD_LEFT);

        // Email kontrolü ve geçici email oluşturma
        $email = !empty($row['email']) ? trim($row['email']) : $this->generateTemporaryEmail($memberNo, $name, $surname);

        // Ödeme yöntemini parse et (nakit, banka_transfer veya boş)
        $paymentMethod = $this->parsePaymentMethod($row['odeme_yontemi'] ?? null);

        // Cinsiyeti parse et (male, female veya boş)
        $gender = $this->parseGender($row['cinsiyet'] ?? null);

        // Üyelik tarihini parse et
        $membershipDate = $this->parseDateOrDefault($row['uyelik_tarihi'] ?? null, \App\Services\DuesValidationService::REFERENCE_DATE);

        // Şifre belirleme mantığı:
        // 1. Excel'de şifre varsa → o şifre kullanılır
        // 2. Excel'de şifre yoksa → şifre null kalır (admin panelinden oluşturulacak)
        $password = null;
        if (!empty($row['sifre']) || !empty($row['password'])) {
            // Excel'de şifre belirtilmişse kullan
            $password = Hash::make($row['sifre'] ?? $row['password']);
        }
        // Şifre yoksa null kalır (geçici email'li üyeler için admin panelinden oluşturulacak)

        return new Member([
            'name' => $name,
            'surname' => $surname,
            'gender' => $gender,
            'email' => $email,
            'phone' => !empty($row['telefon']) ? trim($row['telefon']) : null,
            'birth_date' => $this->parseDateOrDefault($row['dogum_tarihi'] ?? null, '1990-01-01'),
            'birth_place' => $birthPlace ?? 'Bilinmiyor',
            'nationality' => $nationality,
            'occupation' => $occupation ?? 'Bilinmiyor',
            'address' => $address ?? 'Bilinmiyor',
            'member_no' => $memberNo,
            // Use provided membership date as-is (supports Excel serials). If empty, default to reference date
            'membership_date' => $membershipDate,
            'monthly_dues' => isset($row['aylik_aidat']) ? (float) str_replace([','], ['.'], $row['aylik_aidat']) : 5.00,
            'payment_method' => $paymentMethod,
            'status' => $row['durum'] ?? 'active',
            'application_status' => 'approved',
            'password' => $password, // Excel'de varsa kullanılır, yoksa null kalır
            'notes' => $notes,
            // Excel'den yüklenen üyeler için gizlilik rızası otomatik verilmiş sayılsın (eski üyeler gibi)
            'privacy_consent' => true,
            'privacy_consent_date' => $membershipDate,
        ]);
    }

        /**
     * Generate temporary email for members without email
     */
    private function generateTemporaryEmail($memberNo, $name, $surname)
    {
        // Clean name and surname for email
        $cleanName = $this->cleanForEmail($name);
        $cleanSurname = $this->cleanForEmail($surname);

        // Create base email format: ad.soyad@uye.com
        $baseEmail = strtolower($cleanName . '.' . $cleanSurname . '@uye.com');

        // Check if email already exists, if so add number after surname
        $counter = 1;
        $tempEmail = $baseEmail;

        while (Member::where('email', $tempEmail)->exists()) {
            // Format: ad.soyad1@uye.com, ad.soyad2@uye.com, etc.
            $tempEmail = strtolower($cleanName . '.' . $cleanSurname . $counter . '@uye.com');
            $counter++;
        }

        return $tempEmail;
    }

    /**
     * Clean text for email usage
     */
    private function cleanForEmail($text)
    {
        // Remove Turkish characters and replace with ASCII equivalents
        $replacements = [
            'ç' => 'c', 'ğ' => 'g', 'ı' => 'i', 'ö' => 'o', 'ş' => 's', 'ü' => 'u',
            'Ç' => 'C', 'Ğ' => 'G', 'İ' => 'I', 'Ö' => 'O', 'Ş' => 'S', 'Ü' => 'U'
        ];

        $text = str_replace(array_keys($replacements), array_values($replacements), $text);

        // Remove special characters and spaces, keep only letters and numbers
        $text = preg_replace('/[^a-zA-Z0-9]/', '', $text);

        // If empty after cleaning, use 'member'
        if (empty($text)) {
            $text = 'member';
        }

        return $text;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'ad' => 'required|string|max:255',
            'soyad' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email',
            'aylik_aidat' => 'nullable|numeric|min:0',
            'odeme_yontemi' => 'nullable|string',
        ];
    }

    /**
     * Custom attributes for validation errors
     */
    public function customValidationAttributes()
    {
        return [
            'ad' => 'Ad',
            'soyad' => 'Soyad',
            'email' => 'E-mail',
            'aylik_aidat' => 'Aylık Aidat',
            'odeme_yontemi' => 'Ödeme Yöntemi',
        ];
    }

    /**
     * Parse payment method from Excel input
     */
    private function parsePaymentMethod($paymentMethod)
    {
        if (empty($paymentMethod)) {
            return 'bank_transfer'; // Default to bank transfer if empty
        }

        $paymentMethod = strtolower(trim($paymentMethod));

        // Map various input formats to our enum values
        switch ($paymentMethod) {
            case 'nakit':
            case 'cash':
            case 'na':
                return 'cash';
            case 'banka':
            case 'banka transferi':
            case 'banka_transfer':
            case 'bank_transfer':
            case 'bank':
            case 'ba':
                return 'bank_transfer';
            default:
                return 'bank_transfer'; // Default to bank transfer for unknown values
        }
    }

    /**
     * Parse gender from Excel input
     * Supports Turkish and German values
     */
    private function parseGender($gender)
    {
        if (empty($gender)) {
            return null; // Gender is nullable
        }

        // Normalize: trim and handle Turkish/German special characters
        $gender = trim($gender);
        $genderLower = mb_strtolower($gender, 'UTF-8');

        // Map various input formats to our enum values
        // Erkek / Male / Männlich
        if (in_array($genderLower, [
            'erkek', 'e', 'm', 'male', 'männlich', 'maennlich', 
            'm', 'm.', 'männl.', 'maennl.'
        ])) {
            return 'male';
        }

        // Kadın / Female / Weiblich
        if (in_array($genderLower, [
            'kadın', 'kadin', 'kız', 'kiz', 'k', 'f', 'female', 
            'weiblich', 'w', 'w.', 'weibl.'
        ])) {
            return 'female';
        }

        // Case-insensitive check for common variations
        if (stripos($gender, 'erkek') !== false || stripos($gender, 'männlich') !== false || stripos($gender, 'maennlich') !== false) {
            return 'male';
        }

        if (stripos($gender, 'kadın') !== false || stripos($gender, 'kadin') !== false || 
            stripos($gender, 'kız') !== false || stripos($gender, 'kiz') !== false ||
            stripos($gender, 'weiblich') !== false) {
            return 'female';
        }

        return null; // Return null for unknown values (gender is nullable)
    }

    /**
     * Generate password from birth date in ggaayy format
     */
    private function generatePasswordFromBirthDate($birthDate)
    {
        if (empty($birthDate)) {
            return '123456'; // Default password if no birth date
        }

        try {
            $date = Carbon::parse($birthDate);
            return $date->format('dmY'); // ggaayy format
        } catch (\Exception $e) {
            return '123456'; // Fallback to default
        }
    }

    /**
     * Parse date from various inputs (string, Excel serial, DateTime).
     * Falls back to the provided default when empty/invalid.
     */
    private function parseDateOrDefault($date, $default = null)
    {
        // Default değer verilmemişse referans tarihi kullan
        if ($default === null) {
            $default = \App\Services\DuesValidationService::REFERENCE_DATE;
        }
        try {
            if (empty($date)) {
                return Carbon::parse($default);
            }

            // Already a DateTime/Carbon
            if ($date instanceof \DateTimeInterface) {
                return Carbon::instance($date);
            }

            // Excel numeric serial date
            if (is_numeric($date)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date));
            }

            // Try common formats first
            $formats = ['Y-m-d', 'd.m.Y', 'd/m/Y', 'm/d/Y'];
            foreach ($formats as $format) {
                try {
                    return Carbon::createFromFormat($format, (string) $date);
                } catch (\Exception $e) {
                    // continue
                }
            }

            // Fallback to Carbon::parse for other reasonable strings
            return Carbon::parse((string) $date);
        } catch (\Throwable $e) {
            return Carbon::parse($default);
        }
    }

    /**
     * Clean and normalize Turkish text
     */
    private function cleanTurkishText($text)
    {
        if (empty($text)) {
            return $text;
        }

        // Convert to UTF-8 if not already
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

        // Remove BOM if present
        $text = str_replace("\xEF\xBB\xBF", '', $text);

        // Trim whitespace
        $text = trim($text);

        // Fix common encoding issues for Turkish characters
        $replacements = [
            'Ã¼' => 'ü', 'Ã„Â±' => 'ı', 'Ã¶' => 'ö', 'Ã§' => 'ç',
            'Ã„Å¸' => 'ğ', 'Ã…Å¸' => 'ş', 'Ãœ' => 'Ü', 'Ä°' => 'İ',
            'Ã–' => 'Ö', 'Ã‡' => 'Ç', 'Ä' => 'Ğ', 'Å' => 'Ş',
            'â€™' => "'", 'â€œ' => '"', 'â€' => '"', 'â€"' => '-',
        ];

        $text = str_replace(array_keys($replacements), array_values($replacements), $text);

        // Remove any remaining non-printable characters except Turkish chars
        $text = preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}\p{S}]/u', '', $text);

        return $text;
    }

    /**
     * Batch size for bulk inserts
     */
    public function batchSize(): int
    {
        return 50; // Reduced for better memory management
    }

    /**
     * Chunk size for reading
     */
    public function chunkSize(): int
    {
        return 50; // Reduced for better memory management
    }
}
