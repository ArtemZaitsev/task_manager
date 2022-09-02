<?php

namespace App\Console\Commands;

use App\Models\Component\Component;
use App\Models\Component\Sz;
use Illuminate\Console\Command;

class CreateSzFromComponentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:components:sz';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $components = Component::all();
        /** @var Component $component */
        foreach ($components as $component) {
            if ($component->sz_id === null) {
                if (!empty($component->manufactor_sz_files) && $component->manufactor_sz_date !== null) {

                    $sz = $this->findOrCreateSz($component);
                    $component->sz_id = $sz->id;
                    $component->save();

                }
            }
        }

        return 0;
    }

    private function findOrCreateSz(Component $component): Sz
    {
        $rows = Sz::query()
            ->where('title', $component->manufactor_sz_files)
            ->where('date', $component->manufactor_sz_date)
            ->get()
            ->all();
        if (count($rows) === 1) {
            return $rows[0];
        }

        $sz = (new Sz())
            ->fill([
                'date' => $component->manufactor_sz_date,
                'title' => $component->manufactor_sz_files
            ])
            ->save();
        return $sz;

    }

}
