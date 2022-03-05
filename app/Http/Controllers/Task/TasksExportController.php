<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TasksExportController extends Controller
{
    const EXPORT_ACTION = 'tasks_export';

    public function export(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->writeHeader($sheet);
        $this->writeData($sheet, $request->query);
        date_default_timezone_set('Europe/Moscow');
        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
//            'Content-Disposition' => 'attachment;filename="task_list.xlsx"',
            'Content-Disposition' => 'attachment;filename=' . date("Y_m_d H_i_s") . ' перечень задач.xlsx',
        ]);

        return $response;
    }

    private function writeHeader(Worksheet $sheet)
    {
        $headers = [
            'Тема',
            'Основная задача',
            'Название задачи',
            'Ответственный',
            'Дата начала план',
            'Дата окончания план',
            'Приступить',
            'Дата окончания факт',
            'Статус выполнения',
            'Дата обновления план',
            'Дата обновления факт',
            'Что мешает',
            'Что делаем',
        ];

        $this->writeRow($sheet, $headers, 1);

    }

    private function writeData(Worksheet $sheet, InputBag $query)
    {
        $fetcher = new TaskFetcher();
        $tasks = $fetcher->fetchTasks($query);

        /**
         * @var $task Task
         */

        $idx = 2;
        foreach ($tasks as $task) {
            $row = [
                $task->theme,
                $task->main_task,
                $task->name,
                $task->user->label(),
                $task->start_date,
                $task->end_date,
                ($task->execute !== "" && isset(Task::ALL_EXECUTIONS[$task->execute])) ?
                    Task::ALL_EXECUTIONS[$task->execute] : "",
                $task->end_date_fact,
                ($task->status !== "" && isset(Task::ALL_STATUSES[$task->status])) ?
                    Task::ALL_STATUSES[$task->status] : "",

            ];

            if (count($task->logs) === 0) {
                $row = array_merge($row, ['', '', '', '']);
            } elseif (count($task->logs) > 0) {
                $firstRow = $task->logs[0];
                $row = array_merge($row, [
                    $firstRow->date_refresh_plan,
                    $firstRow->date_refresh_fact,
                    $firstRow->trouble,
                    $firstRow->what_to_do,
                ]);
            }

            $this->writeRow($sheet, $row, $idx);
            $idx++;
            if (count($task->logs) > 1) {
                foreach ($task->logs->slice(1) as $taskLog) {
                    $row = array_merge(array_fill(0, 9, ''), [
                        $taskLog->date_refresh_plan,
                        $taskLog->date_refresh_fact,
                        $taskLog->trouble,
                        $taskLog->what_to_do,
                    ]);
                    $this->writeRow($sheet, $row, $idx);
                    $idx++;
                }
                for ($i = 1; $i <= 9; $i++) {
                    $sheet->mergeCellsByColumnAndRow($i, $idx - count($task->logs), $i, $idx - 1);
                }
            }

        }


    }

    private function writeRow(Worksheet $sheet, array $row, int $rowNumber)
    {
        foreach ($row as $idx => $header) {
            $sheet->setCellValueByColumnAndRow($idx + 1, $rowNumber, $header);
        }
    }
}


