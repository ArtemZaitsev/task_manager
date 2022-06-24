<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Audit extends Model
{

    use HasFactory;
    use AsSource;
    use Filterable;

    const EVENT_TYPE_CREATE = 1;
    const EVENT_TYPE_EDIT = 2;
    const EVENT_TYPE_DELETE = 3;

    const ALL_TYPES = [
        self::EVENT_TYPE_CREATE => 'Создание',
        self::EVENT_TYPE_EDIT => 'Редактирование',
        self::EVENT_TYPE_DELETE => 'Удаление',
    ];


    const UPDATED_AT = null;

    protected $table = 'audit';

    protected $fillable = [
        'user_id',
        'event_type',
        'table_name',
        'entity_id',
        'meta_inf',
    ];

    protected $casts = [
        'meta_inf' => 'json',
    ];

    protected $allowedFilters = [
        'id',
        'created_at',
        'user_id',
        'event_type',
        'table_name',
        'entity_id',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
