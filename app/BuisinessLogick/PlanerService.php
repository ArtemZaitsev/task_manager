<?php

namespace App\BuisinessLogick;

use App\Models\Direction;
use App\Models\Family;
use App\Models\Product;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PlanerService
{
    public function userIsPlaner(int $userId): bool
    {
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

        return count($planerDirections) > 0
            || count($planerProject) > 0
            || count($planerFamily) > 0
            || count($planerProduct) > 0;

    }
}
