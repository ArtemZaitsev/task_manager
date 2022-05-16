<?php

namespace App\Http\Controllers\Task\Request;

use App\BuisinessLogick\TaskVoter;

class TaskAddRequest extends BaseTaskRequest
{
    protected function prepareForValidation()
    {
        $this->setDefaultFields();
    }

    public function rules()
    {
        return $this->rules[TaskVoter::ROLE_PLANER];
    }
}
