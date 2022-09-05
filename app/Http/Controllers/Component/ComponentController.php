<?php

namespace App\Http\Controllers\Component;

use App\BuisinessLogick\Voter\ComponentVoter;
use Illuminate\Http\Request;

class ComponentController
{
    public const ROUTE_NAME = 'components.list';
    const RECORDS_PER_PAGE = 30;

    public function __construct(
        private ComponentGrid  $grid,
        private ComponentVoter $voter,

    )
    {

    }

    public function index(Request $request)
    {
        $query = $this->grid->buildQuery($request);

        $components = $query->paginate(self::RECORDS_PER_PAGE)->withQueryString();

        return view('component.list', [
            'data' => $components,
            'grid' => $this->grid,
            'voter' => $this->voter,
            'exportUrl' => route(ComponentExportController::EXPORT_ACTION) . '?' . http_build_query
                ($request->query->all()),
        ]);
    }


}
