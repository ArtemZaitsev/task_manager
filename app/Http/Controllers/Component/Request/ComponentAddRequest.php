<?php

namespace App\Http\Controllers\Component\Request;

use App\BuisinessLogick\Voter\ComponentVoter;
use App\BuisinessLogick\Voter\TaskVoter;

class ComponentAddRequest extends ComponentBaseRequest
{
    public function rules()
    {
        return $this->rules[ComponentVoter::ROLE_PLANER];
    }
}
