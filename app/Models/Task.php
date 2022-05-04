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
    const STATUS_BLOCKED = 6;

    const ALL_STATUSES = [
        self::STATUS_BLOCKED => 'Заблокирована',
        self::STATUS_NOT_DONE => 'Не выполнена',
        self::STATUS_DONE => 'Выполнена',
        self::STATUS_IN_PROGRESS => 'В процессе',
        self::STATUS_INFO => 'Информационная',
        self::STATUS_REFUSE => 'Снята',
    ];


    const EXECUTE_TODAY = 1;
    const EXECUTE_THIS_WEEK = 2;
    const EXECUTE_NEXT_WEEK = 3;
    const EXECUTE_THIS_MONTH = 4;
    const EXECUTE_NEXT_MONTH = 5;
    const EXECUTE_DONT_KNOW = 6;

    const ALL_EXECUTIONS = [
        self::EXECUTE_TODAY => 'Сегодня',
        self::EXECUTE_THIS_WEEK => 'На этой неделе',
        self::EXECUTE_NEXT_WEEK => 'На следующей неделе',
        self::EXECUTE_THIS_MONTH => 'В этом месяце',
        self::EXECUTE_NEXT_MONTH => 'В следующем месяце',
        self::EXECUTE_DONT_KNOW => '-',
    ];

    const PRIORITY_HIGH = 1;
    const PRIORITY_MIDDLE = 2;
    const PRIORITY_LOW = 3;


    const All_PRIORITY = [
        self::PRIORITY_HIGH => 'высокий',
        self::PRIORITY_MIDDLE => 'средний',
        self::PRIORITY_LOW => 'низкий',
    ];

    const TYPE_PLAN = 1;
    const TYPE_NOT_PLAN = 2;

    const All_TYPE = [
        self::TYPE_PLAN => 'плановая',
        self::TYPE_NOT_PLAN => 'внеплановая',
    ];

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


    public function coperformers()
    {
        return $this->belongsToMany(User::class,'task_coperformer');
    }
    public function products(){
        return $this->belongsToMany(Product::class, 'task_product');
    }
    public function families(){
        return $this->belongsToMany(Family::class, 'task_family');
    }
    public function projects(){
        return $this->belongsToMany(Project::class, 'task_project');
    }
}
