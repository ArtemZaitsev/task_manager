<?php

namespace App\Models\Component;

use App\Models\User;
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
        "name",
        "number",
        "head_id",
        ];

    public function head(){
        return $this->belongsTo(User::class);
    }

}
