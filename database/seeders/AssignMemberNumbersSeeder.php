<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Member;

class AssignMemberNumbersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = Member::whereNull('member_no')->orWhere('member_no', '')->get();

        foreach ($members as $index => $member) {
            $memberNumber = 'ÜYE' . str_pad($member->id, 3, '0', STR_PAD_LEFT);
            $member->update(['member_no' => $memberNumber]);
        }

        $this->command->info('Üye numaraları başarıyla atandı.');
    }
}
