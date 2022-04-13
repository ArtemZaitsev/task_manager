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
        'head_id',
        'planer_id',
        'project_id'
    ];

    public function head(){
        return $this->belongsTo(User::class);
    }
    public function project(){
        return $this->belongsTo(Project::class);
    }
    public function planer(){
        return $this->belongsTo(User::class);
    }
    public function label(){
        return $this->title;
    }
    public function tasks(){
        $this->belongsToMany(Task::class, 'task_family');
    }

}
