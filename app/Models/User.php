<?php

namespace App\Models;

use Orchid\Platform\Models\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'email',
        'password',
        'permissions',
        'direction_id',
        'group_id',
        'subgroup_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'email',
        'permissions',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
    ];

    public function label(){
        return $this->surname." ".$this->name." ".$this->patronymic;
    }

    public function getLabelAttribute(){
        return $this->label();
    }


    public function presenter()
    {
        return new UserPresenter($this);
    }

    public function direction(){
        return $this->belongsTo(Direction::class);
    }
    public function group(){
        return $this->belongsTo(Group::class);
    }
    public function subgroup(){
        return $this->belongsTo(Subgroup::class);
    }
    public function coperformedTasks()
    {
        return $this->belongsToMany(Task::class,'task_coperformer');
    }

}
