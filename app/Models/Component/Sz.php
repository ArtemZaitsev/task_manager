<?php

namespace App\Models\Component;


class Sz extends AbstractDocument
{
    protected $table = 'sz';

    protected function documentName(): string
    {
       return 'СЗ';
    }
}
