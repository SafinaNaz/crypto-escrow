<?php

namespace Database\Seeders;

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
        $this->call([
            CountriesTableSeeder::class,
            SiteSettingsTableSeeder::class,
            AdminTableSeeder::class,
            CurrenciesTableSeeder::class,
            TransactionStatusTableSeeder::class,
            EventTypeSeeder::class,
            CmsPagesTableSeeder::class
        ]);
    }
}
