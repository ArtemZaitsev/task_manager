<?php

namespace App\Http\Controllers\Task;

use App\Models\Direction;
use App\Models\Family;
use App\Models\Group;
use App\Models\Product;
use App\Models\Project;
use App\Models\Subgroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Filters\DateFilter;
use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

use function view;


class   TaskController extends BaseController
{
    const ACTION_LIST = 'tasks.list';

    public function list(Request $request)
    {

        $fetcher = new TaskFetcher();
        $tasks = $fetcher->fetchTasks($request->query);


        return view('task_list', [
            'projects' => Project::all(),
            'families' => Family::all(),
            'products' => Product::all(),
            'directions' => Direction::all(),
            'groups' => Group::all(),
            'subgroups' => Subgroup::all(),
            'tasks' => $tasks,
            'users' => User::all(),
            'request' => $request->query,
            'orderFields' => TaskFetcher::ORDER_FIELDS,
            'exportUrl' => route(TasksExportController::EXPORT_ACTION) . '?' . http_build_query($request->query->all()),
        ]);

    }

    /*
     * $columnName - string;
     */

    public static function sortColumn($columnName, Request $request)
    {
        if ($request->has('sort') && $request->get('sort') === $columnName) {
            if ($request->get('sort_direction') === 'ASC') {
                echo "<b style='font-size: 30px; color: green; font-weight: bold;'>&#8593;</b>";
            } else {
                echo "<b style='font-size: 30px; color: green; font-weight: bold;'>&#8595;</b>";
            }
        }
    }

}
