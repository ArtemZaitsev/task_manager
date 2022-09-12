<?php

namespace App\Http\Controllers\TechnicalTaskCalculation;

use App\Http\Controllers\AbstractDocument\AbstractDocumentFileDownloadController;
use App\Models\Component\Sz;
use App\Models\Component\TechnicalTaskCalculation;

class TechnicalTaskCalculationFileDownloadController  extends AbstractDocumentFileDownloadController
{
    public const INDEX_ACTION = 'ttc.file_download';

    protected function entityClass(): string
    {
        return TechnicalTaskCalculation::class;
    }
}

