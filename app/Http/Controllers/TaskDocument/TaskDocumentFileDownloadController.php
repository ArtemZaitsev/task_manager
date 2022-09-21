<?php

namespace App\Http\Controllers\TaskDocument;

use App\Http\Controllers\AbstractDocument\AbstractDocumentFileDownloadController;
use App\Models\TaskDocument;

class TaskDocumentFileDownloadController extends AbstractDocumentFileDownloadController
{
    public const INDEX_ACTION = 'task_document.file_download';

    protected function entityClass(): string
    {
        return TaskDocument::class;
    }
}

