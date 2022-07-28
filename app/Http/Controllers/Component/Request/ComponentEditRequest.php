<?php

namespace App\Http\Controllers\Component\Request;

use App\BuisinessLogick\ComponentVoter;
use App\BuisinessLogick\PlanerService;
use App\BuisinessLogick\TaskVoter;
use App\Http\Controllers\Component\Request\ComponentBaseRequest;
use App\Models\Component\Component;

class ComponentEditRequest extends ComponentBaseRequest
{
    public function rules()
    {
        $entity = $this->currentEntity();

        $voter = new ComponentVoter(new PlanerService());
        return $this->rules[$voter->editRole($entity)];
    }

    private function currentEntity(): Component
    {
        $id = $this->route()->parameter('id');
        $entity = Component::query()->findOrFail($id);
        return $entity;
    }
}
