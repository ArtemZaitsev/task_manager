<?php

namespace App\Http\Controllers\DrawingFile;

use App\BuisinessLogick\FileService;
use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\Component\DrawingFile;

class DrawingFileCreateController extends Controller
{
    public const INDEX_ACTION = 'drawing_file.create.index';
    public const PROCESS_FORM_ACTION = 'drawing_file.create.processForm';

    public function __construct(private FileService $fileService)
    {
    }

    public function index()
    {
        return view('abstract_document.edit', [
            'entity' => new DrawingFile(),
            'title' => 'Добавление чертежей',
        ]);
    }

    public function processForm(DrawingFileRequest $request)
    {
        $entity = new DrawingFile();
        $request->store($entity, $this->fileService);

        return RedirectUtils::redirectBack($request, self::INDEX_ACTION);
    }
}

