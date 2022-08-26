<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Direction extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable = [
        'title',
        'head_id',
        'planer_id'
    ];

    protected $allowedFilters = [
        'title',
    ];

    public function userIsPlaner(int $userId): bool {
        return in_array($userId, $this->planers()->allRelatedIds()->toArray());
    }

    public function head(){
        return $this->belongsTo(User::class);
    }
    public function groups(){
        return $this->hasMany(Group::class);
    }

    public function planers(){
        return $this->belongsToMany(User::class,'direction_planers',
            'direction_id', 'planer_id');
    }
    public function label(){
        return $this->title;
    }
    public function getLabelAttribute(){
        return $this->label();
    }
}
