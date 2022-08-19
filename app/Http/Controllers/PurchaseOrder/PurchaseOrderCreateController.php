<?php

namespace App\Http\Controllers\PurchaseOrder;

use App\BuisinessLogick\FileService;
use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\Component\PurchaseOrder;

class PurchaseOrderCreateController extends Controller
{
    public const INDEX_ACTION = 'purchase_order.create.index';
    public const PROCESS_FORM_ACTION = 'purchase_order.create.processForm';

    public function __construct(private FileService $fileService)
    {
    }

    public function index()
    {
        return view('abstract_document.edit', [
            'entity' => new PurchaseOrder(),
            'title' => 'Добавление заявки'
        ]);
    }

    public function processForm(PurchaseOrderRequest $request)
    {
        $entity = new PurchaseOrder();
        $request->store($entity, $this->fileService);

        return RedirectUtils::redirectBack($request, self::INDEX_ACTION);
    }
}
