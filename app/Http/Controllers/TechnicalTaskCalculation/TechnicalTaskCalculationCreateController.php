<?php

namespace App\Http\Controllers\TechnicalTaskCalculation;

use App\BuisinessLogick\FileService;
use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\Component\TechnicalTaskCalculation;

class TechnicalTaskCalculationCreateController extends Controller
{
    public const INDEX_ACTION = 'ttc.create.index';
    public const PROCESS_FORM_ACTION = 'ttc.create.processForm';

    public function __construct(private FileService $fileService)
    {
    }

    public function index()
    {
        return view('abstract_document.edit', [
            'entity' => new TechnicalTaskCalculation(),
            'title' => 'Добавление ТЗ на расчет',
        ]);
    }

    public function processForm(TechnicalTaskCalculationRequest $request)
    {
        $entity = new TechnicalTaskCalculation();
        $request->store($entity, $this->fileService);

        return RedirectUtils::redirectBack($request, self::INDEX_ACTION);
    }
}

