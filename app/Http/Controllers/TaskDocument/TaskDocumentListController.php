<?php

namespace App\Http\Controllers\TaskDocument;

use App\BuisinessLogick\Voter\TaskDocumentVoter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskDocumentListController extends Controller
{
    public const ROUTE_NAME = 'task_document.list';

    const RECORDS_PER_PAGE = 20;

    public function __construct(
        private TaskDocumentGrid  $grid,
        private TaskDocumentVoter $voter
    )
    {
    }

    public function index(Request $request)
    {
        $query = $this->grid->buildQuery($request);

        $components = $query->paginate(self::RECORDS_PER_PAGE)->withQueryString();

        return view('abstract_document.list', [
            'title' => 'Список документов задачи (основание для постановки задачи)',
            'data' => $components,
            'grid' => $this->grid,
            'voter' => $this->voter,
            'links' => [
                'create' => route(TaskDocumentCreateController::INDEX_ACTION, ['back' => url()->full()]),
                'reset' => route(self::ROUTE_NAME)
            ]
        ]);
    }
}
