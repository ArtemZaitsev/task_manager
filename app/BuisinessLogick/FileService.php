<?php

namespace App\BuisinessLogick;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    private const FILES_SAVE_DIR = __DIR__ . '/../../public/files/';

    public function filePath(string $file): string {
        return self::FILES_SAVE_DIR . $file;
    }

    public function fileExtension(string $fileName): string {
        return pathinfo($fileName, PATHINFO_EXTENSION);
    }
    public function saveUploadedFile(UploadedFile $uploadedFile, string $baseDir): string
    {
        $extension = $this->fileExtension($uploadedFile->getClientOriginalName());

        $hash = hash('sha256', $uploadedFile->getClientOriginalName()) . uniqid();
        $dir = substr($hash, 0, 2) . "/" . substr($hash, 2, 2);
        $fileName = substr($hash, 4) . '.' . $extension;

        if (!is_dir(self::FILES_SAVE_DIR)) {
            throw new \Exception(sprintf('Save dir %s not exist', self::FILES_SAVE_DIR));
        }
        $dirToSave = self::FILES_SAVE_DIR . $baseDir;
        if(!is_dir($dirToSave)) {
            mkdir($dirToSave, 0777, true);
        }

        $fileDir = $dirToSave . "/" . $dir;
        if (!is_dir($fileDir)) {
            mkdir($fileDir, 0777, true);
        }

        $uploadedFile->move($fileDir, $fileName);

        return $baseDir . '/' . $dir . "/" . $fileName;
    }
}
