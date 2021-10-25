<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
//    use HasFactory;
    const STATUS_NEW = 1;
    const STATUS_DONE = 2;
    const STATUS_NOT_DONE = 3;
    const STATUS_IN_PROGRESS = 4;
    const STATUS_REFUSE = 5;

    protected $fillable = ['name','start_date', 'end_date','user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function parent(){
//        return $this->hasOne(Task::class);
        return $this->belongsTo(Task::class);
    }
}
