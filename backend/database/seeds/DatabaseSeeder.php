<?php

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(EmailTemplatesTableSeeder::class);
        $this->call(CmsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
    }
}
