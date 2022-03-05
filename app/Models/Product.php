<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Product extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'title',
        'head_id',
        'planer_id',
        'family_id'
    ];

    public function family(){
        return $this->belongsTo(Family::class);
    }

    public function head(){
        return $this->belongsTo(User::class);
    }

    public function planer(){
        return $this->belongsTo(User::class);
    }

    public function label(){
        return $this->title;
    }

}
