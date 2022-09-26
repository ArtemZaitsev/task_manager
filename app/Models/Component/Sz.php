<?php

namespace App\Models\Component;


use App\Models\User;

/**
 * @property ?User $initiator
 * @property ?User $targetUser
 */
class Sz extends AbstractDocument
{
    protected $table = 'sz';

    protected $fillable = [
        "number",
        "date",
        "title",
        "initiator_id",
        "target_user_id",
    ];

    public function targetUser()
    {
        return $this->belongsTo(User::class);
    }

    public function initiator()
    {
        return $this->belongsTo(User::class);
    }

    protected function documentName(): string
    {
        return 'СЗ';
    }


}
