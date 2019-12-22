<?php

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->insert([
            [
                'id' => 1,
                'menu_title' => 'User Management',
                'route' => 'user-management',
                'component' => 'UserManagementComponent',
                'icon' => 'account-supervisor',
                'parent_id' => NULL,
                'is_active' => true
            ],
            [
                'id' => 2,
                'menu_title' => 'Admin Management',
                'route' => 'admin-management',
                'component' => NULL,
                'icon' => 'account-tie',
                'parent_id' => NULL,
                'is_active' => true
            ],
            [
                'id' => 3,
                'menuTitle' => 'All Admins',
                'route' => 'admin-management',
                'component' => 'AdminManagementComponent',
                'parent_id' => 2,
                'icon' => NULL,
                'is_active' => NULL
            ],
            [
                'id' => 4,
                'menuTitle' => 'Add Admin',
                'route' => 'admin-management/add-admin',
                'component' => 'AddAdminComponent',
                'parent_id' => 2,
                'icon' => NULL,
                'is_active' => NULL
            ],
            [
                'id' => 5,
                'menu_title' => 'Profile',
                'route' => 'profile',
                'component' => 'ProfileComponent',
                'parent_id' => NULL,
                'icon' => 'account-edit',
                'is_active' => true
            ],
            [
                'id' => 6,
                'menu_title' => 'Email Templates',
                'route' => 'email-templates',
                'component' => 'EmailTemplatesComponent',
                'parent_id' => NULL,
                'icon' => 'email-multiple',
                'is_active' => true
            ],
            [
                'id' => 7,
                'menu_title' => 'CMS',
                'route' => 'cms',
                'component' => 'CmsComponent',
                'parent_id' => NULL,
                'icon' => 'table-of-contents',
                'is_active' => true
            ],
            [
                'id' => 8,
                'menu_title' => 'Settings',
                'route' => 'settings',
                'parent_id' => NULL,
                'component' => NULL,
                'icon' => 'settings',
                'is_active' => true
            ],
            [
                'id' => 9,
                'menuTitle' => 'API Settings',
                'route' => 'api-management',
                'component' => 'ApiManagementComponent',
                'parent_id' => 8,
                'icon' => NULL,
                'is_active' => NULL
            ],
            [
                'id' => 10,
                'menuTitle' => 'Add New API',
                'route' => 'api-management/add-api',
                'component' => 'AddApiComponent',
                'parent_id' => 8,
                'icon' => NULL,
                'is_active' => NULL
            ],
        ]);
    }
}
