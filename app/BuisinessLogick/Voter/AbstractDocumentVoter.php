<?php

namespace App\BuisinessLogick\Voter;

interface AbstractDocumentVoter
{
    public function canDelete(): bool;
}
