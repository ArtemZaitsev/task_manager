<?php

namespace App\Http\Controllers\TaskDocument;

use App\Http\Controllers\AbstractDocument\AbstractDocumentRequest;

class TaskDocumentRequest extends AbstractDocumentRequest
{
    public function baseSavePath(): string
    {
        return 'task_document';
    }
}
