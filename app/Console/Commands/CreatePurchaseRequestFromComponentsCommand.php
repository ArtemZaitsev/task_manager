<?php

namespace App\Console\Commands;

use App\Http\Controllers\PurchaseOrder\PurchaseOrderRequest;
use App\Models\Component\Component;
use App\Models\Component\PurchaseOrder;
use App\Models\Component\Sz;
use Illuminate\Console\Command;

class CreatePurchaseRequestFromComponentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:components:purchase_request';

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
        echo sprintf("Total components: %s\n", count($components));

        /** @var Component $component */
        foreach ($components as $component) {
            if ($component->purchase_order_id === null) {
                if (!empty($component->purchase_request_files)
                    && $component->purchase_request_date !== null) {
                    $entity = $this->findOrCreatePurchaseRequest($component);
                    $component->purchase_order_id = $entity->id;
                    $component->save();

                    echo sprintf("Updated component %s\n", $component->id);
                }
            } else {
                echo sprintf("Component %s has sz\n", $component->id);
            }
        }

        return 0;
    }

    private function findOrCreatePurchaseRequest(Component $component): PurchaseOrder
    {
        $rows = PurchaseOrder::query()
            ->where('number', $component->purchase_request_files)
            ->where('date', $component->purchase_request_date)
            ->get()
            ->all();
        if (count($rows) === 1) {
            return $rows[0];
        } elseif (count($rows) > 1) {
            throw  new \Exception(sprintf('multiple entities found, number=%s', $component->purchase_request_files));
        }

        $entity = new PurchaseOrder();
        $entity->fill([
            'number' => $component->purchase_request_files,
            'date' => $component->purchase_request_date,
        ]);
        $entity->file_path='';

        $entity->save();
        return $entity;

    }

}
