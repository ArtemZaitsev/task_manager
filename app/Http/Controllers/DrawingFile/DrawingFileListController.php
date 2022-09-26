<?php

namespace App\Http\Controllers\DrawingFile;

use App\BuisinessLogick\Voter\DrawingFileVoter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DrawingFileListController extends Controller
{
    public const ROUTE_NAME = 'drawing_file.list';

    const RECORDS_PER_PAGE = 20;

    public function __construct(
        private DrawingFileGrid   $grid,
        private DrawingFileVoter $voter
    )
    {
    }

    public function index(Request $request)
    {
        $query = $this->grid->buildQuery($request);

        $components = $query->paginate(self::RECORDS_PER_PAGE)->withQueryString();

        return view('abstract_document.list', [
            'title' => 'Список чертежей',
            'data' => $components,
            'grid' => $this->grid,
            'voter' => $this->voter,
            'links' => [
                'create' => route(DrawingFileCreateController::INDEX_ACTION, ['back' => url()->full()]),
                'reset' => route(self::ROUTE_NAME)
            ]
        ]);
    }
}
