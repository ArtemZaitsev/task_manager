<?php

namespace App\Http\Controllers\DrawingFile;


use App\BuisinessLogick\FileService;
use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\Component\DrawingFile;
use Illuminate\Http\Request;

class DrawingFileEditController extends Controller
{
    public const INDEX_ACTION = 'drawing_file.edit.index';
    public const PROCESS_FORM_ACTION = 'drawing_file.edit.processForm';

    public function __construct(private FileService $fileService)
    {
    }

    public function index(Request $request, int $id)
    {
        $entity = DrawingFile::query()->findOrFail($id);

        return view('abstract_document.edit', [
            'entity' => $entity,
            'title' => 'Редактирование чертежей задачи'
        ]);
    }

    public function processForm(DrawingFileRequest $request, int $id)
    {
        /** @var DrawingFile $entity */
        $entity = DrawingFile::query()->findOrFail($id);
        $request->store($entity, $this->fileService);

        return RedirectUtils::redirectBack($request, self::INDEX_ACTION);
    }
}
