<?php

namespace App\Http\Controllers\Sz;

use App\BuisinessLogick\FileService;
use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\Component\Sz;
use Illuminate\Http\Request;

class SzEditController extends Controller
{
    public const INDEX_ACTION = 'sz.edit.index';
    public const PROCESS_FORM_ACTION = 'sz.edit.processForm';

    public function __construct(private FileService $fileService)
    {
    }

    public function index(Request $request, int $id) {
        $entity = Sz::query()->findOrFail($id);

        return view('abstract_document.edit', [
            'entity' => $entity,
            'title' => 'Редактирование СЗ'
        ]);
    }

    public function processForm(SzRequest $request, int $id)
    {
        /** @var Sz $entity */
        $entity = Sz::query()->findOrFail($id);
        $request->store($entity, $this->fileService);

        return RedirectUtils::redirectBack($request, self::INDEX_ACTION);
    }
}
