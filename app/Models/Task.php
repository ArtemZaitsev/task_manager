<?php

namespace App\Models;

use App\Models\Component\Component;
use App\Models\Component\PhysicalObject;
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
    const STATUS_DELEGATE = 7;
    const STATUS_NOT_DATES = 8;

    const ALL_STATUSES = [
        self::STATUS_NOT_DONE => 'Не выполнена. Есть сроки',
        self::STATUS_NOT_DATES => 'Не выполнена. Нет сроков',
        self::STATUS_IN_PROGRESS => 'В процессе',
        self::STATUS_DONE => 'Выполнена',
        self::STATUS_BLOCKED => 'Заблокирована',
        self::STATUS_INFO => 'Информационная',
        self::STATUS_REFUSE => 'Снята',
        self::STATUS_DELEGATE => 'Делегирована',
    ];

    const STATUSES_STRING = [
        self::STATUS_BLOCKED => 'STATUS_BLOCKED',
        self::STATUS_NOT_DATES => 'STATUS_NOT_DATES',
        self::STATUS_NOT_DONE => 'STATUS_NOT_DONE',
        self::STATUS_DONE => 'STATUS_DONE',
        self::STATUS_IN_PROGRESS => 'STATUS_IN_PROGRESS',
        self::STATUS_INFO => 'STATUS_INFO',
        self::STATUS_REFUSE => 'STATUS_REFUSE',
        self::STATUS_DELEGATE => 'STATUS_DELEGATE',

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
        self::PRIORITY_MIDDLE => 'повышенный',
        self::PRIORITY_LOW => 'обычный',
    ];

    const TYPE_PLAN = 1;
    const TYPE_NOT_PLAN = 2;

    const All_TYPE = [
        self::TYPE_PLAN => 'плановая',
        self::TYPE_NOT_PLAN => 'внеплановая',
    ];

    protected $fillable = [
        'number',
        'base',
        'setting_date',
        'task_creator',
        'priority',
        'type',
        'theme',
        'main_task',
        'parent_id',
        'name',
        'user_id',
        'system_id',
        'subsystem_id',
        'detail_id',
        'physical_object_id',
        'start_date',
        'end_date',
        'end_date_plan',
        'end_date_fact',
        'progress',
        'execute',
        'status',
        'comment',
        'execute_time_plan',
        'execute_time_fact',
        'component_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(TaskLog::class);
    }

    public function coperformers()
    {
        return $this->belongsToMany(User::class, 'task_coperformer');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'task_product');
    }

    public function families()
    {
        return $this->belongsToMany(Family::class, 'task_family');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'task_project');
    }

    public function physicalObject()
    {
        return $this->belongsTo(PhysicalObject::class);
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    public function parent()
    {
        return $this->belongsTo(Task::class);
    }

    public function prev()
    {
        return $this->belongsToMany(Task::class, 'tasks_prev','task_id', 'task_prev_id');
    }


}
