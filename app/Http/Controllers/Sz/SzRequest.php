<?php

namespace App\Http\Controllers\Sz;

use App\Http\Controllers\AbstractDocument\AbstractDocumentRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class SzRequest  extends AbstractDocumentRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
           'initiator_id' => ['nullable', Rule::exists(User::class, 'id')],
            'target_user_id' => ['nullable', Rule::exists(User::class, 'id')],
        ]);
    }

    public function baseSavePath(): string
    {
        return 'sz';
    }
}
