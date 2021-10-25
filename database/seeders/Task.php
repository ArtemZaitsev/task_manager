<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Task extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tasks')->insert([
            'name' => 'task2',
            'person_id' => '1',
            'start_date' => '2021-07-22 10:40:02',
            'end_date' => '2021-08-22 10:40:02',
            'status' => \App\Models\Task::STATUS_NEW,

        ]);
    }
}
