<?php

namespace App\Http\Controllers\Component;

use App\Http\Controllers\Task\TaskFetcher;
use App\Lib\Grid\GridColumn;
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

    public function __construct(
        private ComponentGrid $grid
    )
    {
    }

    public function export(Request $request)
    {
        $query = $this->grid->buildQuery($request);
        $components = $query->get()->all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->writeHeader($sheet, $this->grid);
        $this->writeData($sheet, $components, $this->grid);

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
        $columns = array_filter($grid->getColumns(), fn(GridColumn $column) => $column->isNeedExport());

        $columnLabels = array_map(
            fn(GridColumn $column) => $column->getLabel(),
            $columns
        );

        $this->writeRow($sheet, $columnLabels, 1);

    }

    private function writeData(Worksheet $sheet, $components, ComponentGrid $grid)
    {
        $idx = 2;
        $columns = array_filter($grid->getColumns(), fn(GridColumn $column) => $column->isNeedExport());

        foreach ($components as $component) {
            $row = array_map(
                fn(GridColumn $column) => $column->renderExcel($component),
                $columns
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
