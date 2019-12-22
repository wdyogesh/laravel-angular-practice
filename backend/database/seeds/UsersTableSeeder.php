<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'role_id' => 1,
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'is_active' => '1',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'mobile_code'       => 91,
                'mobile'            => 9876543210,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ],
            [
                'id' => 2,
                'role_id' => 2,
                'first_name' => 'User',
                'last_name' => 'User',
                'is_active' => '1',
                'email' => 'user@example.com',
                'email_verified_at' => now(),
                'mobile_code'       => 91,
                'mobile'            => 9876543210,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ],
            [
                'id' => 3,
                'role_id' => 3,
                'first_name' => 'Smartdata',
                'last_name' => 'Smartdata',
                'is_active' => '1',
                'email' => 'superadmin@example.com',
                'email_verified_at' => now(),
                'mobile_code'       => 91,
                'mobile'            => 9876543210,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ]
        ]);
        factory(App\User::class, 30)->create();
    }
}
