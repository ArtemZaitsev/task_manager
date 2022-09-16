<?php

namespace App\BuisinessLogick;

use App\Models\Family;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class PlanerService
{
    private array $cache = [];

    public function userIsPlaner(int $userId): bool
    {
        if(isset($this->cache[$userId])) {
            return $this->cache[$userId];
        }

        $planerDirections = DB::select('SELECT * from direction_planers WHERE planer_id = :planer_id', [
            'planer_id' => $userId
        ]);

        $planerProject = Project::where('planer_id', $userId)
            ->select('id')
            ->limit(1)
            ->get();
        $planerProduct = Product::where('planer_id', $userId)
            ->select('id')
            ->limit(1)
            ->get();
        $planerFamily = Family::where('planer_id', $userId)
            ->select('id')
            ->limit(1)
            ->get();

        $userIsPlaner = count($planerDirections) > 0
            || count($planerProject) > 0
            || count($planerFamily) > 0
            || count($planerProduct) > 0;

        $this->cache[$userId] = $userIsPlaner;
        return $userIsPlaner;

    }
}
