<?php

namespace App\Http\Controllers\Component\Request;

use App\BuisinessLogick\Voter\ComponentVoter;
use App\BuisinessLogick\TaskVoter;

class ComponentAddRequest extends ComponentBaseRequest
{
    public function rules()
    {
        return $this->rules[ComponentVoter::ROLE_PLANER];
    }
}
