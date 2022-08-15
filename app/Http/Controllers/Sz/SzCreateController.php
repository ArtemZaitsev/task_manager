<?php

namespace App\Http\Controllers\Sz;

use App\BuisinessLogick\FileService;
use App\Http\Controllers\Component\ComponentController;
use App\Http\Controllers\Controller;
use App\Models\Component\Sz;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class SzCreateController extends Controller
{
    public const INDEX_ACTION = 'sz.index';
    public const PROCESS_FORM_ACTION = 'sz.processForm';

    public function __construct(private FileService $fileService)
    {
    }

    public function index()
    {
        return view('sz.edit', [
            'entity' => new Sz()
        ]);
    }

    public function processForm(Request $request)
    {
        $data = $request->validate([
            'number' => ['required', 'max:255'],
            'date' => ['required', 'date'],
        ]);

        $entity = new Sz();
        $entity->fill($data);

        if ($request->files->has('szFile')) {
            $file = $request->files->get('szFile');
            $fileName = $this->fileService->saveUploadedFile($file, 'sz');
            $entity->file_path = $fileName;
        }

        $entity->save();
        return redirect(route(ComponentController::ROUTE_NAME));

    }
}
