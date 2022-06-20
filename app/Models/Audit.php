<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    const EVENT_TYPE_CREATE = 1;
    const EVENT_TYPE_EDIT = 2;
    const EVENT_TYPE_DELETE = 3;

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

    public function user(){
        return $this->belongsTo(User::class);
    }

}
