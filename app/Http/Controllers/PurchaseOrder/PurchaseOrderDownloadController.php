<?php

namespace App\Http\Controllers\PurchaseOrder;

use App\Http\Controllers\AbstractDocument\AbstractDocumentFileDownloadController;
use App\Models\Component\PurchaseOrder;

class PurchaseOrderDownloadController extends AbstractDocumentFileDownloadController
{
    public const INDEX_ACTION = 'purchase_order.file_download';

    protected function entityClass(): string
    {
        return PurchaseOrder::class;
    }
}
