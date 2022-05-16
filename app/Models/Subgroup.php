<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Subgroup extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'title',
        'group_id',
        'head_id'
    ];

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function head(){
        return $this->belongsTo(User::class);
    }

    public function label(){
        return $this->title." - ". $this->group->title ." - ". $this->group->direction-> title;
    }
    public function getLabelAttribute(){
        return $this->label();
    }

    public function getFullNameAttribute(){
        return $this->label(). ' ('. $this->group?->label .' . ' . $this->group?->direction?->label .')';
    }
}
