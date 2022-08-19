<?php

namespace App\Http\Controllers\TechnicalTaskCalculation;

use App\Http\Controllers\AbstractDocument\AbstractDocumentRequest;

class TechnicalTaskCalculationRequest  extends AbstractDocumentRequest
{
    public function baseSavePath(): string
    {
        return 'ttc';
    }
}
