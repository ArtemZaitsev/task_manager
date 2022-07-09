<?php

namespace App\Models\Component;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Detail extends Model
{

    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable= [
        "name",
        "number",
        "head_id",
        "subsystem_id",
        "system_id",
    ];

    public function head(){
        return $this->belongsTo(User::class);
    }

    public function subsystem(){
        return $this->belongsTo(Subsystem::class);
    }
    public function system(){
        return $this->belongsTo(System::class);
    }

    public function label(){
        return $this->name;
    }
}
