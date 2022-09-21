<?php

namespace App\Http\Controllers\TaskDocument;

use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\TaskDocument;
use Illuminate\Http\Request;

class TaskDocumentDeleteController extends Controller
{
    public const ROUTE_NAME = 'task_document.delete';

    public function index(Request $request, $id)
    {
        try {
            $entity = TaskDocument::query()->findOrFail($id);
            $entity->delete();
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при удалении: ' . $e->getMessage());
        }

        return RedirectUtils::redirectBack($request, self::ROUTE_NAME);
    }
}
