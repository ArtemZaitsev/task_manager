<?php

namespace App\Http\Controllers\TechnicalTaskCalculation;

use App\BuisinessLogick\Voter\TechnicalTaskCalculationVoter;
use App\Http\Controllers\Controller;
use App\Models\Component\TechnicalTaskCalculation;
use Illuminate\Http\Request;

class TechnicalTaskCalculationListController extends Controller
{
    public const ROUTE_NAME = 'ttc.list';

    const RECORDS_PER_PAGE = 20;

    public function __construct(
        private TechnicalTaskCalculationGrid $grid,
        private TechnicalTaskCalculationVoter $voter
    )
    {
    }

    public function index(Request $request)
    {
        $query = $this->grid->buildQuery($request);

        $components = $query->paginate(self::RECORDS_PER_PAGE)->withQueryString();

        return view('abstract_document.list', [
            'title' => 'Список ТЗ на расчет',
            'data' => $components,
            'grid' => $this->grid,
            'voter' => $this->voter,
            'links' => [
                'create' => route(TechnicalTaskCalculationCreateController::INDEX_ACTION, ['back' => url()->full()]),
                'reset' => route(self::ROUTE_NAME)
            ]
        ]);
    }
}
