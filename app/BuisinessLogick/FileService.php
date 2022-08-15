<?php

namespace App\BuisinessLogick;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{

    public function saveUploadedFile(UploadedFile $uploadedFile, string $baseDir): string
    {
        $extension = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_EXTENSION);

        $hash = hash('sha256', $uploadedFile->getClientOriginalName()) . uniqid();
        $dir = substr($hash, 0, 2) . "/" . substr($hash, 2, 2);
        $fileName = substr($hash, 4) . '.' . $extension;

        $dirToSave = __DIR__ . '/../../public/files/' . $baseDir;
        if (!is_dir($dirToSave)) {
            throw new \Exception(sprintf('Save dir %s not exist', $dirToSave));
        }

        $fileDir = $dirToSave . "/" . $dir;
        if (!is_dir($fileDir)) {
            mkdir($fileDir, 0777, true);
        }

        $uploadedFile->move($fileDir, $fileName);

        return $baseDir . '/' . $dir . "/" . $fileName;
    }
}
