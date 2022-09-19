<?php

namespace App\Models\Component;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Metasystem extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable= [
        "title",
    ];
    protected $allowedFilters = [
        'title',
    ];
    public function label(){
        return $this->title;
    }
}
