<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    protected $table = 'task_log';

    protected $fillable = [
        'task_id',
        'date_refresh_plan',
        'date_refresh_fact',
        'trouble',
        'status',
        'what_to_do'
    ];

    const STATUS_NOT_DONE = 1;
    const STATUS_DONE = 2;

    const ALL_STATUSES = [
        self::STATUS_NOT_DONE => 'Блокирует',
        self::STATUS_DONE => 'Решена',

    ];
    public function task(){
        return $this->belongsTo(Task::class);
    }
}
