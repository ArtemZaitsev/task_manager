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
        'planer_id',
        'family_id'
    ];

    public function family(){
        return $this->belongsTo(Family::class);
    }

    public function heads(){
        return $this->belongsToMany(User::class, 'product_heads');
    }

    public function planer(){
        return $this->belongsTo(User::class);
    }

    public function label(){
        return $this->title." - ". $this->family->title." - ". $this->family->project->title;
    }

    public function tasks(){
        return $this->belongsToMany(Task::class, 'task_product');
    }
}
