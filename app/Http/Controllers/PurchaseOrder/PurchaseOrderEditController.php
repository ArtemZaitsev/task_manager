<?php

namespace App\Http\Controllers\PurchaseOrder;

use App\BuisinessLogick\FileService;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Sz\SzRequest;
use App\Lib\RedirectUtils;
use App\Models\Component\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderEditController extends Controller
{
    public const INDEX_ACTION = 'purchase_order.edit.index';
    public const PROCESS_FORM_ACTION = 'purchase_order.edit.processForm';

    public function __construct(private FileService $fileService)
    {
    }

    public function index(Request $request, int $id) {
        $entity = PurchaseOrder::query()->findOrFail($id);

        return view('abstract_document.edit', [
            'entity' => $entity,
            'title' => 'Редактирование заявки'
        ]);
    }

    public function processForm(SzRequest $request, int $id)
    {
        /** @var PurchaseOrder $entity */
        $entity = PurchaseOrder::query()->findOrFail($id);
        $request->store($entity, $this->fileService);

        return RedirectUtils::redirectBack($request, self::INDEX_ACTION);
    }
}
