<?php

namespace App\Http\Controllers\PurchaseOrder;

use App\Http\Controllers\AbstractDocument\AbstractDocumentRequest;

class PurchaseOrderRequest extends AbstractDocumentRequest
{
    public function baseSavePath(): string
    {
        return 'purchase_order';
    }
}
