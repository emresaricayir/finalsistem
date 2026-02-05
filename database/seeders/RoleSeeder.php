<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Süper Admin',
                'description' => 'Tüm yetkilere sahip sistem yöneticisi',
                'permissions' => [
                    'users.manage',
                    'members.manage',
                    'members.view',
                    'members.create',
                    'members.edit',
                    'members.delete',
                    'payments.manage',
                    'payments.view',
                    'payments.create',
                    'payments.edit',
                    'payments.delete',
                    'dues.manage',
                    'dues.view',
                    'dues.create',
                    'dues.edit',
                    'dues.delete',
                    'reports.view',
                    'reports.export',
                    'content.manage',
                    'content.view',
                    'content.create',
                    'content.edit',
                    'content.delete',
                    'settings.manage',
                    'settings.view',
                    'settings.edit'
                ]
            ],
            [
                'name' => 'editor',
                'display_name' => 'Editör',
                'description' => 'İçerik yönetimi ve haber/duyuru ekleme yetkisi',
                'permissions' => [
                    'content.view',
                    'content.create',
                    'content.edit',
                    'content.delete',
                    'members.view'
                ]
            ],
            [
                'name' => 'accountant',
                'display_name' => 'Muhasip',
                'description' => 'Ödeme ve aidat yönetimi yetkisi',
                'permissions' => [
                    'members.view',
                    'members.edit',
                    'payments.manage',
                    'payments.view',
                    'payments.create',
                    'payments.edit',
                    'payments.delete',
                    'dues.manage',
                    'dues.view',
                    'dues.create',
                    'dues.edit',
                    'dues.delete',
                    'reports.view',
                    'reports.export'
                ]
            ],
            [
                'name' => 'education',
                'display_name' => 'Eğitim',
                'description' => 'Eğitim üyeleri ve aidatları yönetimi yetkisi',
                'permissions' => [
                    'education.members.view',
                    'education.members.create',
                    'education.members.edit',
                    'education.members.delete',
                    'education.payments.view',
                    'education.payments.create',
                    'education.payments.edit',
                    'education.payments.delete',
                    'education.dues.view',
                    'education.dues.manage'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        // Assign super_admin role to existing admin users
        $adminUsers = User::where('is_admin', true)->get();
        $superAdminRole = Role::where('name', 'super_admin')->first();

        foreach ($adminUsers as $user) {
            if ($superAdminRole && !$user->hasRole('super_admin')) {
                $user->assignRole('super_admin');
            }
        }
    }
}
