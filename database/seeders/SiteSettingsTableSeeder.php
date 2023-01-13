<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SiteSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('site_settings')->delete();

        \DB::table('site_settings')->insert([
            [
                'id' => 1,
                'site_name' => 'Safeland',
                'site_title' => 'Safeland',
                'site_keywords' => 'Safeland',
                'site_description' => 'Safeland'
            ]
        ]);
    }
}
