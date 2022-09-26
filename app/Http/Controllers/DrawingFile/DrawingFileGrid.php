<?php

namespace App\Http\Controllers\DrawingFile;

use App\BuisinessLogick\PlanerService;
use App\Http\Controllers\AbstractDocument\AbstractDocumentGrid;
use App\Models\Component\DrawingFile;
use Illuminate\Database\Eloquent\Builder;

class DrawingFileGrid  extends AbstractDocumentGrid
{
    public function __construct(PlanerService $planerService)
    {
        parent::__construct(
            $planerService,
            'drawing_file',
            DrawingFileEditController::INDEX_ACTION,
            DrawingFileDeleteController::ROUTE_NAME,
            DrawingFileDownloadController::INDEX_ACTION
        );
    }

    protected function baseQuery(): Builder
    {
        return DrawingFile::query();
    }
}
