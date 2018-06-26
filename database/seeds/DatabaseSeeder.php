<?php

use Illuminate\Database\Seeder;
use App\Process;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Create an example process
        $process = factory(Process::class)->create([
            'bpmn' => file_get_contents('bpmn/Lanes.bpmn'),
        ]);
        echo "Process created: ", $process->uid, "\n";
    }
}
