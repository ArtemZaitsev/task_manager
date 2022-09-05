<?php

namespace App\Http\Controllers\AbstractDocument;

use App\BuisinessLogick\FileService;
use App\Models\Component\AbstractDocument;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractDocumentRequest extends FormRequest
{
    public function rules() {
        return [
            'number' => ['required', 'max:255'],
            'title' => ['nullable', 'max:255'],
            'date' => ['required', 'date'],
        ];
    }

    public function store(AbstractDocument $entity, FileService $fileService): void {
        $entity->fill($this->validated());

        if ($this->files->has('file')) {
            $file = $this->files->get('file');
            $fileName = $fileService->saveUploadedFile($file, $this->baseSavePath());
            $entity->file_path = $fileName;
        }

        $entity->save();
    }

    public abstract function baseSavePath(): string;
}
