<?php

namespace App\Models\Component;

use App\Models\User;

class DrawingFile extends AbstractDocument
{
    protected $table = 'drawing_files';

    protected $fillable = [
        "number",
        "date",
        "title",
    ];


    protected function documentName(): string
    {
        return 'Чертежи';
    }


}
