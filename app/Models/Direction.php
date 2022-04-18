<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Direction extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'title',
        'head_id',
        'planer_id'
    ];
    public function head(){
        return $this->belongsTo(User::class);
    }
    public function groups(){
        return $this->hasMany(Group::class);
    }

    public function planer(){
        return $this->belongsTo(User::class);
    }
    public function label(){
        return $this->title;
    }
    public function getLabelAttribute(){
        return $this->label();
    }
}
