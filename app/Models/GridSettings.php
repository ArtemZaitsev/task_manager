<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GridSettings extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'grid',
        'settings_data',
    ];

    protected $casts = [
        'settings_data' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
