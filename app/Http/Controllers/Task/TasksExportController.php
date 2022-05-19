<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Product;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
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

            'Приоритет',
            'Тип',
            'Основание',
            'Дата постановки',
            'Постановщик',
            'Направление',
            'Группа',
            'Подгруппа',
            'Проект',
            'Семейство',
            'Продукт',
            'Тема',
            'Основная задача',
            'Задача',
            'Ответственный',
            'Соисполнители',
            'Дата установленная руководителем',
            'Дата окончания план',
            'Дата окончания факт',
            'Приступить',
            'Статус выполнения',
            'Кол-во ч/ч план',
            'Кол-во ч/ч факт',
            'Статус проблемы',
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
                \App\Models\Task::All_PRIORITY[$task->priority],
                \App\Models\Task::All_TYPE[$task->type],
                $task->base,
                \App\Utils\DateUtils::dateToDisplayFormat($task->setting_date),
                $task->task_creator,
                $task->user->direction?->title,
                $task->user->group?->title,
                $task->user->subgroup?->title,
                implode(', ', $task->projects->map(fn(Project $entity)=> $entity->title)->toArray()),
                implode(', ', $task->families->map(fn(Family $entity)=> $entity->title)->toArray()),
                implode(', ', $task->products->map(fn(Product $entity)=> $entity->title)->toArray()),
                $task->theme,
                $task->main_task,
                $task->name,
                $task->user->label(),
                implode(', ', $task->coperformers->map(fn(User $entity)=> $entity->label)->toArray()),
                \App\Utils\DateUtils::dateToDisplayFormat($task->end_date),
                \App\Utils\DateUtils::dateToDisplayFormat($task->end_date_plan),
                \App\Utils\DateUtils::dateToDisplayFormat($task->end_date_fact),
                ($task->execute !== "" && isset(Task::ALL_EXECUTIONS[$task->execute])) ?
                    Task::ALL_EXECUTIONS[$task->execute] : "",
                ($task->status !== "" && isset(Task::ALL_STATUSES[$task->status])) ?
                    Task::ALL_STATUSES[$task->status] : "",
                $task->execute_time_plan,
                $task->execute_time_fact,
            ];

            if (count($task->logs) === 0) {
                $row = array_merge($row, ['', '', '', '', '']);
            } elseif (count($task->logs) > 0) {
                $firstRow = $task->logs[0];
                $row = array_merge($row, [
                    $firstRow->status,
                    \App\Utils\DateUtils::dateToDisplayFormat($firstRow->date_refresh_plan),
                    \App\Utils\DateUtils::dateToDisplayFormat( $firstRow->date_refresh_fact),
                    $firstRow->trouble,
                    $firstRow->what_to_do,
                ]);
            }

            $this->writeRow($sheet, $row, $idx);
            $idx++;
            if (count($task->logs) > 1) {
                foreach ($task->logs->slice(1) as $taskLog) {
                    $row = array_merge(array_fill(0, 9, ''), [
                        $taskLog->status,
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


