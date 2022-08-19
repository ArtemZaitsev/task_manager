<?php

namespace App\BuisinessLogick\Voter;

use App\BuisinessLogick\Voter\AbstractDocumentVoter;

class SzVoter implements AbstractDocumentVoter
{
    public function canDelete(): bool {
        return true;
    }

}
