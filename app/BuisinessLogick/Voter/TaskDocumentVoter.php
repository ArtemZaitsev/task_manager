<?php

namespace App\BuisinessLogick\Voter;

class TaskDocumentVoter implements AbstractDocumentVoter
{
    public function canDelete(): bool {
        return true;
    }

}
