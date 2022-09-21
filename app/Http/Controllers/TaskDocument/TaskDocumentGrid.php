<?php

namespace App\Http\Controllers\TaskDocument;

use App\BuisinessLogick\PlanerService;
use App\Http\Controllers\AbstractDocument\AbstractDocumentGrid;
use App\Models\TaskDocument;
use Illuminate\Database\Eloquent\Builder;

class TaskDocumentGrid  extends AbstractDocumentGrid
{
    public function __construct(PlanerService $planerService)
    {
        parent::__construct(
            $planerService,
            'task_document',
            TaskDocumentEditController::INDEX_ACTION,
            TaskDocumentDeleteController::ROUTE_NAME,
            TaskDocumentFileDownloadController::INDEX_ACTION
        );
    }

    protected function baseQuery(): Builder
    {
        return TaskDocument::query();
    }
}
