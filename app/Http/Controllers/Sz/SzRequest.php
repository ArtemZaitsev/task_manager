<?php

namespace App\Http\Controllers\Sz;

use App\Http\Controllers\AbstractDocument\AbstractDocumentRequest;

class SzRequest  extends AbstractDocumentRequest
{
    public function baseSavePath(): string
    {
        return 'sz';
    }
}
