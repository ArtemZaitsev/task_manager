<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Group extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable =[
        'title',
        'direction_id',
        'head_id'
    ];
    public function head(){
        return $this->belongsTo(User::class);
    }
    public function direction(){
        return $this->belongsTo(Direction::class);
    }

    public function subgroups(){
        return $this->hasMany(Subgroup::class);
    }

    public function label(){
        return $this->title;
    }
    public function getLabelAttribute(){
        return $this->label();
    }

    public function getFullNameAttribute(){
        return $this->label(). ' ('. $this->direction?->label .')';
    }
}
