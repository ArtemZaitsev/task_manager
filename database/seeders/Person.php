<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Person extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('person')->insert([
            'name' => 'Иванов',
            'surname' => 'Иван',
            'patronymic' => 'Иванович',
        ]);
    }
}
