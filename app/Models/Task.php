<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
//    use HasFactory;
    const STATUS_NOT_DONE = 1;
    const STATUS_DONE = 2;
    const STATUS_IN_PROGRESS = 3;
    const STATUS_INFO = 4;
    const STATUS_REFUSE = 5;

    protected $fillable = ['name', 'start_date', 'end_date', 'user_id'];
    protected $casts = [
        'number' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
//        return $this->hasOne(Task::class);
        return $this->belongsTo(Task::class);
    }

    public function logs()
    {
        return $this->hasMany(TaskLog::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function coperformers()
    {
        return $this->belongsToMany(User::class,'task_coperformer');
    }
}
