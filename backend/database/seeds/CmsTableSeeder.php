<?php

use Illuminate\Database\Seeder;

class CmsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cms')->insert([
            'flag'             => 'footer_bottom',
            'display_title'    => Str::random(10),
            'original_content' => Str::random(10),
            'content'          => 'ind',
        ]);
    }
}
