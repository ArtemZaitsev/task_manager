<?php

namespace App\Models;

use App\Models\Component\AbstractDocument;

class TaskDocument extends AbstractDocument
{
    protected function documentName(): string
    {
        return '';
    }
}
