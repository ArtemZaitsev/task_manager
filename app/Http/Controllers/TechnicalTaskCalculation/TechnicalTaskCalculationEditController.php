<?php

namespace App\Http\Controllers\TechnicalTaskCalculation;



use App\BuisinessLogick\FileService;
use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\Component\TechnicalTaskCalculation;
use Illuminate\Http\Request;

class TechnicalTaskCalculationEditController extends Controller
{
    public const INDEX_ACTION = 'ttc.edit.index';
    public const PROCESS_FORM_ACTION = 'ttc.edit.processForm';

    public function __construct(private FileService $fileService)
    {
    }

    public function index(Request $request, int $id) {
        $entity = TechnicalTaskCalculation::query()->findOrFail($id);

        return view('abstract_document.edit', [
            'entity' => $entity,
            'title' => 'Редактирование ТЗ на расчет'
        ]);
    }

    public function processForm(TechnicalTaskCalculationRequest $request, int $id)
    {
        /** @var TechnicalTaskCalculation $entity */
        $entity = TechnicalTaskCalculation::query()->findOrFail($id);
        $request->store($entity, $this->fileService);

        return RedirectUtils::redirectBack($request, self::INDEX_ACTION);
    }
}
