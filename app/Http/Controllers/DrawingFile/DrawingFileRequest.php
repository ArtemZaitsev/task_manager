<?php

namespace App\Http\Controllers\DrawingFile;

use App\Http\Controllers\AbstractDocument\AbstractDocumentRequest;

class DrawingFileRequest extends AbstractDocumentRequest
{
    public function baseSavePath(): string
    {
        return 'drawing_file';
    }
}
