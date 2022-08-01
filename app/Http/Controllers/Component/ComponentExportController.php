<?php

namespace App\Http\Controllers\Component;

use App\Http\Controllers\Task\TaskFetcher;
use App\Models\Component\Component;
use App\Models\Family;
use App\Models\Product;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ComponentExportController
{
    const EXPORT_ACTION = 'components.export';

    public function export(Request $request)
    {
        $grid = new ComponentGrid();
        $query = $grid->buildQuery($request);
        $components = $query->get()->all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->writeHeader($sheet, $grid);
        $this->writeData($sheet, $components, $grid);

        date_default_timezone_set('Europe/Moscow');
        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
//            'Content-Disposition' => 'attachment;filename="task_list.xlsx"',
            'Content-Disposition' => 'attachment;filename=' . date("Y_m_d H_i_s") . ' перечень компонентов.xlsx',
        ]);

        return $response;
    }

    private function writeHeader(Worksheet $sheet, ComponentGrid $grid)
    {
        $columnLabels = array_map(
            fn(GridColumn $column) => $column->getLabel(),
            $grid->getColumns()
        );

        $this->writeRow($sheet, $columnLabels, 1);

    }

    private function writeData(Worksheet $sheet, $components, ComponentGrid $grid)
    {
        $idx = 2;
        foreach ($components as $component) {
            $row = array_map(
                fn(GridColumn $column) => $column->renderExcel($component),
                $grid->getColumns()
            );

            $this->writeRow($sheet, $row, $idx);
            $idx++;
        }


    }

    private function writeRow(Worksheet $sheet, array $row, int $rowNumber)
    {
        foreach ($row as $idx => $header) {
            $sheet->setCellValueByColumnAndRow($idx + 1, $rowNumber, $header);
        }
    }
}
