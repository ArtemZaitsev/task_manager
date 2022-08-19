<?php

namespace App\Models\Component;

use App\Utils\DateUtils;

class PurchaseOrder extends AbstractDocument
{
    protected function documentName(): string
    {
        return 'Заявка';
    }
}
