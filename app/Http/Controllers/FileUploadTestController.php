<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadTestController extends Controller
{

    public function index(){
        return view('file_upload');
    }

    public function processForm(Request $request) {
        /** @var UploadedFile $fileInput */
        $fileInput = $request->files->get('fileInput');
        $extension = pathinfo($fileInput->getClientOriginalName(), PATHINFO_EXTENSION);

        $hash = hash('sha256', $fileInput->getClientOriginalName()) . uniqid();
        $dir = substr($hash, 0, 2) . "/" . substr($hash, 2, 2);
        $fileName = substr($hash, 4) . '.' . $extension;

        $dirToSave = __DIR__ . '/../../../public/files/sz';
        if(!is_dir($dirToSave)) {
            throw new \Exception(sprintf('Save dir %s not exist', $dirToSave));
        }

        $fileDir = $dirToSave . "/" . $dir;
        if(!is_dir($fileDir)) {
            mkdir($fileDir, 0777, true);
        }

        $fileInput->move($fileDir, $fileName);

        return view('file_upload');
    }
}
