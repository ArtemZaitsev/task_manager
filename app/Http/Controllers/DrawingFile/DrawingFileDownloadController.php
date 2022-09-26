<?php

namespace App\Http\Controllers\DrawingFile;

use App\Http\Controllers\AbstractDocument\AbstractDocumentFileDownloadController;
use App\Models\Component\DrawingFile;

class DrawingFileDownloadController extends AbstractDocumentFileDownloadController
{
    public const INDEX_ACTION = 'drawing_file.file_download';

    protected function entityClass(): string
    {
        return DrawingFile::class;
    }
}

