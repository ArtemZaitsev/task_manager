<?php

namespace App\Http\Controllers\Task;


use App\Http\Controllers\Filters\DateFilter;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

use function view;

class TaskController extends BaseController
{

    const ACTION_LIST = 'tasks.list';


    public function list(Request $request) {

        $orderFields = [
            'name' => 'Имя',
            'start_date' => 'Дата начала',
            'end_date' => 'Дата окончания',
            'status' => 'Статус',
        ];

        $tasksQuery = Task::with('user')->where('user_id',Auth::id());
        if($request->query->has('task_name')){
            $taskName = trim($request->query->get('task_name'));
            if($taskName !== ""){
                $tasksQuery->where('name','like','%'.$taskName.'%');
            }
        }

        if($request->query->has('start_date')) {
            $startDateFilter = $request->query->get('start_date');
            $this->applyDateFilter($startDateFilter, $tasksQuery,'start_date');
        }

        if($request->query->has('end_date')) {
            $endDateFilter = $request->query->get('end_date');
            $this->applyDateFilter($endDateFilter, $tasksQuery,'end_date');
        }

        if($request->query->has('order_column')) {

            $orderColumn = $request->query->get('order_column');
            if(isset($orderFields[$orderColumn]) ){
                $tasksQuery->orderBy($orderColumn,$request->query->get('order_direction'));
//            $this->applyDateFilter($endDateFilter, $tasksQuery,'end_date');
            }
        }

        $tasks = $tasksQuery->get();
        return view('task_list', [
            'tasks' => $tasks,
            'request' => $request->query,
            'orderFields' => $orderFields,
        ]);
    }

    private function applyDateFilter(array $filterDate, \Illuminate\Database\Eloquent\Builder $query, string $columnName){
        $currentDate = new \DateTime();

        switch($filterDate['mode']){
            case '':

                break;
            case DateFilter::MODE_TODAY:
                $query->where($columnName,'>=',$currentDate->format('Y-m-d').' 00:00:00')
                    ->where($columnName, '<=', $currentDate->format('Y-m-d').' 23:59:59');

                break;
            case DateFilter::MODE_RANGE:
                if($filterDate['start'] !== '' && $filterDate['start'] !== null){
                    $query->where($columnName,'>=',$filterDate['start'] .' 00:00:00');
                }
                if($filterDate['end'] !== '' && $filterDate['end'] !== null){
                    $query->where($columnName,'<=',$filterDate['end'] .' 23:59:59');
                }

                break;
            default:
                throw new \Exception('Invalid mode');
        }
    }

}
