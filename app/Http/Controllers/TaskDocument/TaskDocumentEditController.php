<?php

namespace App\Http\Controllers\TaskDocument;


use App\BuisinessLogick\FileService;
use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\TaskDocument;
use Illuminate\Http\Request;

class TaskDocumentEditController extends Controller
{
    public const INDEX_ACTION = 'task_document.edit.index';
    public const PROCESS_FORM_ACTION = 'task_document.edit.processForm';

    public function __construct(private FileService $fileService)
    {
    }

    public function index(Request $request, int $id)
    {
        $entity = TaskDocument::query()->findOrFail($id);

        return view('abstract_document.edit', [
            'entity' => $entity,
            'title' => 'Редактирование документа задачи'
        ]);
    }

    public function processForm(TaskDocumentRequest $request, int $id)
    {
        /** @var TaskDocument $entity */
        $entity = TaskDocument::query()->findOrFail($id);
        $request->store($entity, $this->fileService);

        return RedirectUtils::redirectBack($request, self::INDEX_ACTION);
    }
}
