<?php

namespace App\Http\Controllers\DrawingFile;

use App\Http\Controllers\Controller;
use App\Lib\RedirectUtils;
use App\Models\Component\DrawingFile;
use Illuminate\Http\Request;

class DrawingFileDeleteController extends Controller
{
    public const ROUTE_NAME = 'drawing_file.delete';

    public function index(Request $request, $id)
    {
        try {
            $entity = DrawingFile::query()->findOrFail($id);
            $entity->delete();
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при удалении: ' . $e->getMessage());
        }

        return RedirectUtils::redirectBack($request, self::ROUTE_NAME);
    }
}
