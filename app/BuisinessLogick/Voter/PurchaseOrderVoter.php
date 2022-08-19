<?php

namespace App\BuisinessLogick\Voter;

class PurchaseOrderVoter implements AbstractDocumentVoter
{
    public function canDelete(): bool {
        return true;
    }

}

