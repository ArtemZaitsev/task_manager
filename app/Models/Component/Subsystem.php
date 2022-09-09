<?php

namespace App\Models\Component;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Subsystem extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable= [
        "title",
        'system_id'
    ];

    protected $allowedFilters = [
        'title',
    ];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function label(){
        return $this->title;
    }
}

