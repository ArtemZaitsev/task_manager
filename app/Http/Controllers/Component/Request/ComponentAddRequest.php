<?php

namespace App\Http\Controllers\Component\Request;

use App\BuisinessLogick\ComponentVoter;
use App\BuisinessLogick\TaskVoter;

class ComponentAddRequest extends ComponentBaseRequest
{
    public function rules()
    {
        return $this->rules[ComponentVoter::ROLE_PLANER];
    }
}
