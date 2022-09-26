<?php

namespace App\Http\Controllers\AbstractDocument;

use App\BuisinessLogick\FileService;
use App\Http\Controllers\Controller;
use App\Models\Component\AbstractDocument;
use Illuminate\Http\Request;

abstract class AbstractDocumentFileDownloadController extends Controller
{
    public function __construct(private FileService $fileService)
    {
    }

    protected abstract function entityClass(): string;

    public function index(Request $request, $id)
    {
        $entityClass = $this->entityClass();
        $entity = $entityClass::query()->findOrFail($id);
        asset($entity instanceof AbstractDocument);

        if (empty($entity->file_path)) {
            throw new \LogicException();
        }

        $filePath = $this->fileService->filePath($entity->file_path);
        $fileName = $this->fileName($entity);
        return response()->file($filePath, ['Content-Disposition' => sprintf('inline; filename="%s"',
            $fileName)]);
        //  return response()->json(['success']);
    }

    private function fileName(AbstractDocument $entity): string
    {
        $extension = $this->fileService->fileExtension($entity->file_path);
        return $entity->label() . "." . $extension;
    }
}

