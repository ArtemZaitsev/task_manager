<?php

namespace App\BuisinessLogick\Voter;

class DrawingFileVoter implements AbstractDocumentVoter
{
    public function canDelete(): bool {
        return true;
    }

}

