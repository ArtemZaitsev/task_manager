<?php

namespace App\Models\Component;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class PhysicalObject extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    const TARGET_1 = 1;
    const TARGET_2 = 2;
    const TARGET_3 = 3;

    const ALL_TARGETS = [
        self::TARGET_1 => 'Заказчик',
        self::TARGET_2 => 'Испытания',
        self::TARGET_3 => 'Маркетинг',
    ];


    protected $fillable= [
        "name",
        "target",
    ];


    public function label(){
        return $this->name;
    }



}
