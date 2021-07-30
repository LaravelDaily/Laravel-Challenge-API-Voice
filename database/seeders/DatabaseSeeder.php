<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected $seeds = [
        UserSeeder::class,
        QuestionSeeder::class,
        VoiceSeeder::class,
    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->seeds as $seedClass) {
            $this->call($seedClass);
        }
    }
}
