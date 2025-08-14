<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->delete();

        $resources = [
            'device',
            'device_type',
            'widget',
            'remote_control',
            'user',
            'admin',
            'role',
            'faq',
            'case_study',
            'content_hub',
            'blog',
            'case_study_category',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];


        $permissions = [];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'name' => "{$action}_{$resource}",
                    'guard_name' => "web",
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $customPermissions = [
            'send_notification',
            'create_permission',
        ];

        foreach ($customPermissions as $key) {
            $permissions[] = [
                'name' => $key,
                'guard_name' => "web",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('permissions')->insert($permissions);

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $allPermissions = Permission::all();
        $superAdmin->syncPermissions($allPermissions);

        $admin = Admin::where('email', 'admin@admin.com')->first();

        if ($admin) {
            if (!$admin->hasRole($superAdmin->name)) {
                $admin->assignRole($superAdmin);
            }
        }
    }
}
