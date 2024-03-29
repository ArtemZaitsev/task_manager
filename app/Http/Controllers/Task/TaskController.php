<?php

namespace App\Http\Controllers\Task;

use App\BuisinessLogick\PlanerService;
use App\BuisinessLogick\Voter\ProjectVoter;
use App\BuisinessLogick\Voter\TaskVoter;
use App\Models\Component\Component;
use App\Models\Component\PhysicalObject;
use App\Models\Direction;
use App\Models\Family;
use App\Models\Group;
use App\Models\Product;
use App\Models\Project;
use App\Models\Subgroup;
use App\Models\Task;
use App\Models\TaskDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use function view;


class TaskController extends BaseController
{
    const ACTION_LIST = 'tasks.list';


    public function __construct(
        private TaskVoter     $taskVoter,
        private PlanerService $planerService,
        private ProjectVoter  $projectVoter,

    )
    {

    }

    public function list(Request $request)
    {
        $fetcher = new TaskFetcher();
        $tasks = $fetcher->fetchTasks($request->query);
        $tasksHasChilds = $this->buildTasksHasChilds($tasks->items());

        $sum = [
            'execute_time_plan' => $fetcher->sumByColumn('execute_time_plan', $request->query),
            'execute_time_fact' => $fetcher->sumByColumn('execute_time_fact', $request->query),
        ];


        return view('task.task_list', [
            'taskVoter' => $this->taskVoter,
            'projectVoter' => $this->projectVoter,
            'planerService' => $this->planerService,
            'projects' => Project::all(),
            'families' => Family::all(),
            'products' => Product::all(),
            'physical_objects' => PhysicalObject::all(),
            'directions' => Direction::all(),
            'groups' => Group::all(),
            'subgroups' => Subgroup::all(),
            'components' => Component::all('id', 'title', 'identifier'),
            'tasks' => $tasks,
            'sum' => $sum,
            'users' => User::all(),
            'tasksHasChilds' => $tasksHasChilds,
            'request' => $request->query,
            'orderFields' => TaskFetcher::ORDER_FIELDS,
            'exportUrl' => route(TasksExportController::EXPORT_ACTION) . '?' . http_build_query($request->query->all()),
            'taskDocument' => TaskDocument::all(),
        ]);

    }

    /*
     * $columnName - string;
     */

    public static function sortColumn($columnName, Request $request)
    {
        if ($request->has('sort') && $request->get('sort') === $columnName) {
            if ($request->get('sort_direction') === 'ASC') {
                echo "<b style='color: green; font-weight: bold;'>
<svg width='24' height='24' fill='green' class='bi bi-arrow-bar-up' viewBox='0 0 16 16'>
  <path fill-rule='evenodd' d='M8 10a.5.5 0 0 0 .5-.5V3.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 3.707V9.5a.5.5 0 0 0 .5.5zm-7 2.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5z'/>
</svg>
</b>";
            } else {
                echo "<b style='color: green; font-weight: bold;'>
<svg  width='24' height='24' fill='green' class='bi bi-arrow-bar-down' viewBox='0 0 16 16'>
  <path fill-rule='evenodd' d='M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5zM8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6z'/>
</svg>
</b>";
            }
        }
    }

    private function buildTasksHasChilds(array $tasks): array
    {
        if(count($tasks) === 0) {
            return [];
        }

        $taskIds = array_map(fn(Task $task) => $task->id, $tasks);
        $taskIdsStr= implode(',', $taskIds);

        $sql = <<<SQL
SELECT t.id, (SELECT COUNT(*) from tasks t2 where t2.parent_id = t.id) as childs_count
from tasks t
WHERE t.id IN ({$taskIdsStr})
SQL;

        $rows = DB::select($sql);

        $result = [];
        foreach ($rows as $row) {
            $result[$row->id] = ((int) $row->childs_count) > 0;
        }

        return $result;
    }

}
