<?php

namespace App\Http\Controllers\Sz;

use App\BuisinessLogick\FileService;
use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\Component\Sz;

class SzCreateController extends Controller
{
    public const INDEX_ACTION = 'sz.create.index';
    public const PROCESS_FORM_ACTION = 'sz.create.processForm';

    public function __construct(private FileService $fileService)
    {
    }

    public function index()
    {
        return view('abstract_document.edit', [
            'entity' => new Sz(),
            'title' => 'Добавление СЗ'
        ]);
    }

    public function processForm(SzRequest $request)
    {
        $entity = new Sz();
        $request->store($entity, $this->fileService);

        return RedirectUtils::redirectBack($request, self::INDEX_ACTION);
    }
}
