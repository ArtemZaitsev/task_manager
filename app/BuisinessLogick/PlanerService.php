<?php

namespace App\BuisinessLogick;

use App\Models\Direction;
use App\Models\Family;
use App\Models\Product;
use App\Models\Project;
use App\Models\User;

class PlanerService
{
    public function userIsPlaner(int $userId): bool
    {
        $planerDirections = Direction::where('planer_id', $userId)
            ->select('id')
            ->limit(1)
            ->get();

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
