<?php

namespace App\Http\Controllers\Sz;

use App\BuisinessLogick\SzVoter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SzListController extends Controller
{
    public const ROUTE_NAME = 'sz.list';

    const RECORDS_PER_PAGE = 20;

    public function __construct(
        private SzGrid $grid,
        private SzVoter $voter
    )
    {
    }

    public function index(Request $request)
    {
        $query = $this->grid->buildQuery($request);

        $components = $query->paginate(self::RECORDS_PER_PAGE)->withQueryString();

        return view('sz.list', [
            'data' => $components,
            'grid' => $this->grid,
            'voter' => $this->voter,
            'links' => [
                'create' => route(SzCreateController::INDEX_ACTION, ['back' => url()->full()]),
                'reset' => route(self::ROUTE_NAME)
            ]
        ]);
    }

}
