<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EducationMember;
use Carbon\Carbon;

class EducationMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'name' => 'Ahmet',
                'surname' => 'Yılmaz',
                'student_name' => 'Mehmet',
                'student_surname' => 'Yılmaz',
                'email' => 'ahmet.yilmaz@email.com',
                'phone' => '0532 123 45 67',
                'status' => 'active',
                'membership_date' => '2024-09-01',
                'monthly_dues' => 500.00,
                'notes' => 'Örnek üye 1',
            ],
            [
                'name' => 'Fatma',
                'surname' => 'Demir',
                'student_name' => 'Ayşe',
                'student_surname' => 'Demir',
                'email' => 'fatma.demir@email.com',
                'phone' => '0533 234 56 78',
                'status' => 'active',
                'membership_date' => '2024-09-01',
                'monthly_dues' => 450.00,
                'notes' => 'Örnek üye 2',
            ],
            [
                'name' => 'Mustafa',
                'surname' => 'Kaya',
                'student_name' => 'Ali',
                'student_surname' => 'Kaya',
                'email' => 'mustafa.kaya@email.com',
                'phone' => '0534 345 67 89',
                'status' => 'active',
                'membership_date' => '2024-09-15',
                'monthly_dues' => 600.00,
                'notes' => 'Örnek üye 3',
            ],
            [
                'name' => 'Zeynep',
                'surname' => 'Özkan',
                'student_name' => 'Elif',
                'student_surname' => 'Özkan',
                'email' => 'zeynep.ozkan@email.com',
                'phone' => '0535 456 78 90',
                'status' => 'active',
                'membership_date' => '2024-10-01',
                'monthly_dues' => 550.00,
                'notes' => 'Örnek üye 4',
            ],
            [
                'name' => 'İbrahim',
                'surname' => 'Çelik',
                'student_name' => 'Emre',
                'student_surname' => 'Çelik',
                'email' => 'ibrahim.celik@email.com',
                'phone' => '0536 567 89 01',
                'status' => 'active',
                'membership_date' => '2024-10-01',
                'monthly_dues' => 480.00,
                'notes' => 'Örnek üye 5',
            ],
            [
                'name' => 'Hatice',
                'surname' => 'Şahin',
                'student_name' => 'Selin',
                'student_surname' => 'Şahin',
                'email' => 'hatice.sahin@email.com',
                'phone' => '0537 678 90 12',
                'status' => 'active',
                'membership_date' => '2024-10-15',
                'monthly_dues' => 520.00,
                'notes' => 'Örnek üye 6',
            ],
            [
                'name' => 'Osman',
                'surname' => 'Arslan',
                'student_name' => 'Berk',
                'student_surname' => 'Arslan',
                'email' => 'osman.arslan@email.com',
                'phone' => '0538 789 01 23',
                'status' => 'active',
                'membership_date' => '2024-11-01',
                'monthly_dues' => 650.00,
                'notes' => 'Örnek üye 7',
            ],
            [
                'name' => 'Gül',
                'surname' => 'Koç',
                'student_name' => 'Deniz',
                'student_surname' => 'Koç',
                'email' => 'gul.koc@email.com',
                'phone' => '0539 890 12 34',
                'status' => 'active',
                'membership_date' => '2024-11-01',
                'monthly_dues' => 580.00,
                'notes' => 'Örnek üye 8',
            ],
            [
                'name' => 'Hasan',
                'surname' => 'Polat',
                'student_name' => 'Can',
                'student_surname' => 'Polat',
                'email' => 'hasan.polat@email.com',
                'phone' => '0540 901 23 45',
                'status' => 'active',
                'membership_date' => '2024-11-15',
                'monthly_dues' => 500.00,
                'notes' => 'Örnek üye 9',
            ],
            [
                'name' => 'Sema',
                'surname' => 'Yıldız',
                'student_name' => 'Ece',
                'student_surname' => 'Yıldız',
                'email' => 'sema.yildiz@email.com',
                'phone' => '0541 012 34 56',
                'status' => 'active',
                'membership_date' => '2024-12-01',
                'monthly_dues' => 470.00,
                'notes' => 'Örnek üye 10',
            ],
            [
                'name' => 'Murat',
                'surname' => 'Aydın',
                'student_name' => 'Kaan',
                'student_surname' => 'Aydın',
                'email' => 'murat.aydin@email.com',
                'phone' => '0542 123 45 67',
                'status' => 'inactive',
                'membership_date' => '2024-09-01',
                'monthly_dues' => 600.00,
                'notes' => 'Pasif üye örneği',
            ],
            [
                'name' => 'Pınar',
                'surname' => 'Güneş',
                'student_name' => 'Lara',
                'student_surname' => 'Güneş',
                'email' => 'pinar.gunes@email.com',
                'phone' => '0543 234 56 78',
                'status' => 'suspended',
                'membership_date' => '2024-10-01',
                'monthly_dues' => 550.00,
                'notes' => 'Askıya alınmış üye örneği',
            ],
        ];

        foreach ($members as $memberData) {
            EducationMember::create($memberData);
        }

        $this->command->info('12 adet örnek eğitim üyesi oluşturuldu.');
    }
}