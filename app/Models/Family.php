<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Family extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable =[
        'title',
        'planer_id',
        'project_id'
    ];

    public function heads(){
        return $this->belongsToMany(User::class, 'family_heads');
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }
    public function planer(){
        return $this->belongsTo(User::class);
    }
    public function label(){
        return $this->title." - ". $this->project->title;
    }
    public function tasks(){
        $this->belongsToMany(Task::class, 'task_family');
    }

}
