<?php

namespace App\Http\Controllers\Sz;

use App\Http\Controllers\AbstractDocument\AbstractDocumentFileDownloadController;
use App\Models\Component\Sz;

class SzFileDownloadController extends AbstractDocumentFileDownloadController
{
    public const INDEX_ACTION = 'sz.file_download';

    protected function entityClass(): string
    {
        return Sz::class;
    }
}
