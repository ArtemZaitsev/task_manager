<?php

namespace App\BuisinessLogick\Voter;

class TechnicalTaskCalculationVoter implements AbstractDocumentVoter
{
    public function canDelete(): bool {
        return true;
    }

}
