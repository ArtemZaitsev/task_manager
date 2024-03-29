<?php

namespace App\Models\Component;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class System extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable= [
        "title",
        "metasystem_id"
    ];
    protected $allowedFilters = [
        'title',
    ];

    public function metasystem()
    {
        return $this->belongsTo(Metasystem::class);
    }

    public function label(){
        return $this->title;
    }
}
