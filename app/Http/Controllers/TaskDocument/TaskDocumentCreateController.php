<?php

namespace App\Http\Controllers\TaskDocument;

use App\BuisinessLogick\FileService;
use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\TaskDocument;

class TaskDocumentCreateController extends Controller
{
    public const INDEX_ACTION = 'task_document.create.index';
    public const PROCESS_FORM_ACTION = 'task_document.create.processForm';

    public function __construct(private FileService $fileService)
    {
    }

    public function index()
    {
        return view('abstract_document.edit', [
            'entity' => new TaskDocument(),
            'title' => 'Добавление документа задачи',
        ]);
    }

    public function processForm(TaskDocumentRequest $request)
    {
        $entity = new TaskDocument();
        $request->store($entity, $this->fileService);

        return RedirectUtils::redirectBack($request, self::INDEX_ACTION);
    }
}

