<?php

namespace App\BuisinessLogick\Voter;

class SzVoter implements AbstractDocumentVoter
{
    public function canDelete(): bool {
        return true;
    }

}
