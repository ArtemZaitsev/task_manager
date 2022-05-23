<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Project extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable = [
        'title',
        'planer_id'
    ];
    protected $allowedFilters = [
        'title',
    ];
    public function heads(){
        return $this->belongsToMany(User::class,'project_user');
    }
    public function planer(){
        return $this->belongsTo(User::class);
    }
    public function label(){
        return $this->title;
    }
    public function tasks(){
        $this->belongsToMany(Task::class, 'task_project');
    }
}
