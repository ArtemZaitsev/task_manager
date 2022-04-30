<?php

namespace App\Http\Controllers\Task;

use App\BuisinessLogick\PlanerService;
use App\Http\Controllers\Filters\DateFilter;
use App\Models\Direction;
use App\Models\Family;
use App\Models\Group;
use App\Models\Product;
use App\Models\Project;
use App\Models\Subgroup;
use App\Models\Task;
use App\Models\TaskLog;
use Doctrine\DBAL\Query\QueryBuilder;
use http\Env\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\InputBag;

class TaskFetcher
{

    const ORDER_FIELDS = [
        'name' => 'Имя',
        'start_date' => 'Дата начала',
        'end_date' => 'Дата окончания',
        'status' => 'Статус',
    ];

    private PlanerService $planerService;

    public function __construct()
    {
        $this->planerService = new PlanerService();
    }

    private function applyFilters(InputBag $query, Builder $tasksQuery)
    {
        if ($query->has('project')) {
            $project = $query->get('project');
            if (!empty($project)) {
                $tasksQuery->whereIn('tasks.id', function ($query) use ($project) {
                    $query->select('tproj.task_id')
                        ->from('task_project', 'tproj')
                        ->whereIn('tproj.project_id', $project);
                });
            }
        }
        if ($query->has('family')) {
            $family = $query->get('family');
            if (!empty($family)) {
                $tasksQuery->whereIn('tasks.id', function ($query) use ($family) {
                    $query->select('tf.task_id')
                        ->from('task_family', 'tf')
                        ->whereIn('tf.family_id', $family);
                });
            }
        }
        if ($query->has('product')) {
            $product = $query->get('product');
            if (!empty($product)) {
                $tasksQuery->whereIn('tasks.id', function ($query) use ($product) {
                    $query->select('tprod.task_id')
                        ->from('task_product', 'tprod')
                        ->whereIn('tprod.product_id', $product);
                });
            }
        }
        if ($query->has('direction')) {
            $execute = $query->get('direction');
            $this->applyMultipleValuesFilter($execute, $tasksQuery, 'directions.id');
        }
        if ($query->has('group')) {
            $execute = $query->get('group');
            $this->applyMultipleValuesFilter($execute, $tasksQuery, 'groups.id');
        }

        if ($query->has('subgroup')) {
            $execute = $query->get('subgroup');
            $this->applyMultipleValuesFilter($execute, $tasksQuery, 'subgroups.id');
        }

        if ($query->has('base')) {
            $baseFilter = trim($query->get('base'));
            if ($baseFilter !== "") {
                $tasksQuery->where('base', 'like', '%' . $baseFilter . '%');
            }
        }

        if ($query->has('setting_date')) {
            $settingDateFilter = $query->get('setting_date');
            $this->applyDateFilter($settingDateFilter, $tasksQuery, 'setting_date');
        }

        if ($query->has('task_creator')) {
            $taskCreatorFilter = trim($query->get('task_creator'));
            if ($taskCreatorFilter !== "") {
                $tasksQuery->where('task_creator', 'like', '%' . $taskCreatorFilter . '%');
            }
        }

        if ($query->has('priority')) {
            $priorityFilter = $query->get('priority');
            if ($priorityFilter !== '' && $priorityFilter !== null) {
                $tasksQuery->where('tasks.priority', $priorityFilter);
            }
        }

        if ($query->has('type')) {
            $typeFilter = $query->get('type');
            if ($typeFilter !== '' && $typeFilter !== null) {
                $tasksQuery->where('tasks.type', $typeFilter);
            }
        }

        if ($query->has('theme')) {
            $theme = trim($query->get('theme'));
            if ($theme !== "") {
                $tasksQuery->where('tasks.theme', 'like', '%' . $theme . '%');
            }
        }

        if ($query->has('main_task')) {
            $main_task = trim($query->get('main_task'));
            if ($main_task !== "") {
                $tasksQuery->where('tasks.main_task', 'like', '%' . $main_task . '%');
            }
        }


        if ($query->has('task_name')) {
            $taskName = trim($query->get('task_name'));
            if ($taskName !== "") {
                $tasksQuery->where('tasks.name', 'like', '%' . $taskName . '%');
            }
        }

        if ($query->has('start_date')) {
            $startDateFilter = $query->get('start_date');
            $this->applyDateFilter($startDateFilter, $tasksQuery, 'start_date');
        }

        if ($query->has('end_date')) {
            $endDateFilter = $query->get('end_date');
            $this->applyDateFilter($endDateFilter, $tasksQuery, 'end_date');
        }

        if ($query->has('end_date_fact')) {
            $endDateFilterFact = $query->get('end_date_fact');
            $this->applyDateFilter($endDateFilterFact, $tasksQuery, 'end_date_fact');
        }

        if ($query->has('status')) {
            $status = $query->get('status');
            if ($status !== '' && $status !== null) {
                $tasksQuery->where('status', $status);
            }
        }


        if ($query->has('user')) {
            $execute = $query->get('user');
            $this->applyMultipleValuesFilter($execute, $tasksQuery, 'user_id');
        }

        if ($query->has('coperformer')) {
            $project = $query->get('coperformer');
            if (!empty($project)) {
                $tasksQuery->whereIn('tasks.id', function ($query) use ($project) {
                    $query->select('tl8.task_id')
                        ->from('task_coperformer', 'tl8')
                        ->whereIn('tl8.user_id', $project);
                });
            }
        }

        if ($query->has('execute')) {
            $execute = $query->get('execute');
            $this->applyMultipleValuesFilter($execute, $tasksQuery, 'execute');
        }


        if ($query->has('order_column')) {

            $orderColumn = $query->get('order_column');
            if (isset(self::ORDER_FIELDS[$orderColumn])) {
                $tasksQuery->orderBy($orderColumn, $query->get('order_direction'));
//            $this->applyDateFilter($endDateFilter, $tasksQuery,'end_date');
            }
        }

        if ($query->has('task_log_status')) {
            $taskLogStatus = $query->get('task_log_status');
            $taskLogStatus = $this->clearArray($taskLogStatus);

            if (!empty($taskLogStatus)) {
                $tasksQuery->whereIn('tasks.id', function ($query) use ($taskLogStatus) {
                    $query->select('tl3.task_id')
                        ->from((new TaskLog())->getTable(), 'tl3')
                        ->whereIn('tl3.status', $taskLogStatus);
                });
            }
        }

        if ($query->has('date_refresh_plan')) {
            $dateRefreshPlan = $query->get('date_refresh_plan');
            if (!empty($dateRefreshPlan) && DateFilter::filterEnabled($dateRefreshPlan)) {
                $tasksQuery->whereIn('tasks.id', function ($query) use ($dateRefreshPlan) {
                    $query->select('tl4.task_id')
                        ->from((new TaskLog())->getTable(), 'tl4');
                    $this->applyDateFilter($dateRefreshPlan, $query, 'date_refresh_plan');
                });
            }
        }

        if ($query->has('date_refresh_fact')) {
            $dateRefreshFact = $query->get('date_refresh_fact');
            if (!empty($dateRefreshFact) && DateFilter::filterEnabled($dateRefreshFact)) {
                $tasksQuery->whereIn('tasks.id', function ($query) use ($dateRefreshFact) {
                    $query->select('tl5.task_id')
                        ->from((new TaskLog())->getTable(), 'tl5');
                    $this->applyDateFilter($dateRefreshFact, $query, 'date_refresh_fact');
                });
            }
        }


        if ($query->has('trouble')) {
            $trouble = $query->get('trouble');
            $trouble = trim($trouble);
            if (!empty($trouble)) {
                $tasksQuery->whereIn('tasks.id', function ($query) use ($trouble) {
                    $query->select('tl6.task_id')
                        ->from((new TaskLog())->getTable(), 'tl6')
                        ->where('tl6.trouble', 'like', '%' . $trouble . '%');
                });
            }
        }

        if ($query->has('what_to_do')) {
            $whatToDo = $query->get('what_to_do');
            $whatToDo = trim($whatToDo);
            if (!empty($whatToDo)) {
                $tasksQuery->whereIn('tasks.id', function ($query) use ($whatToDo) {
                    $query->select('tl7.task_id')
                        ->from((new TaskLog())->getTable(), 'tl7')
                        ->where('tl7.what_to_do', 'like', '%' . $whatToDo . '%');
                });
            }
        }
    }

    private function applySort(InputBag $query, Builder $tasksQuery)
    {
        $orderColumns = [
            'base' => 'base',
            'setting_date' => 'setting_date',
            'task_creator' => 'task_creator',
            'priority' => 'priority',
            'type' => 'type',
            'main_task' => 'main_task',
            'name' => 'name',
            'user' => 'user_id',
            'start_date' => 'start_date',
            'end_date' => 'end_date',
            'execute' => 'execute',
            'status' => 'status'


        ];

        if ($query->has('sort')) {
            $sortColumn = trim($query->get('sort'));
            $sortDirection = $query->get('sort_direction');

            if (isset($orderColumns[$sortColumn])) {
                $dbColumn = $orderColumns[$sortColumn];
                $tasksQuery->orderBy($dbColumn, $sortDirection);
            } else {
                switch ($sortColumn) {
                    case 'subgroup':
                        $tasksQuery->orderBy('subgroups.title', $sortDirection);
                        break;
                    case 'group':
                        $tasksQuery->orderBy('groups.title', $sortDirection);
                        break;
                    case 'direction':
                        $tasksQuery->orderBy('directions.title', $sortDirection);
                        break;
                    case 'product':
                        $tasksQuery->orderBy('products.title', $sortDirection);
                        break;
                    case 'family':
                        $tasksQuery->orderBy('families.title', $sortDirection);
                        break;
                    case 'project':
                        $tasksQuery->orderBy('projects.title', $sortDirection);
                        break;
                }
            }
        }
    }

    public function fetchTasks(InputBag $query)
    {

        $tasksQuery = Task::with('user')
            ->with('logs');

        $tasksQuery->leftJoin('users', 'users.id', '=', 'tasks.user_id');
        $tasksQuery->select('tasks.*');

        $this->filterByPermissions($tasksQuery);


        if ($query->get('sort') === 'subgroup' || count($query->get('subgroup') ?? []) > 0) {
            $tasksQuery->leftJoin('subgroups', 'subgroups.id', '=', 'users.subgroup_id');
            $tasksQuery->select('tasks.*');
        }

        if ($query->get('sort') === 'group' || count($query->get('group') ?? []) > 0) {
            $tasksQuery->leftJoin('groups', 'groups.id', '=', 'users.group_id');
            $tasksQuery->select('tasks.*');
        }
        if ($query->get('sort') === 'direction' || count($query->get('direction') ?? []) > 0) {
            $tasksQuery->leftJoin('directions', 'directions.id', '=', 'users.direction_id');
            $tasksQuery->select('tasks.*');
        }

        $this->applyFilters($query, $tasksQuery);
        $this->applySort($query, $tasksQuery);


        $tasks = $tasksQuery->paginate(300);
        return $tasks;
    }

    private function applyDateFilter(array $filterDate, $query, string $columnName)
    {
        /** @var Builder $query */

        $currentDate = new \DateTime();

        switch ($filterDate['mode']) {
            case '':

                break;
            case DateFilter::MODE_TODAY:
                $query->where($columnName, '>=', $currentDate->format('Y-m-d') . ' 00:00:00')
                    ->where($columnName, '<=', $currentDate->format('Y-m-d') . ' 23:59:59');

                break;
            case DateFilter::MODE_RANGE:
                if (isset($filterDate['start']) && $filterDate['start'] !== '' && $filterDate['start'] !== null) {
                    $query->where($columnName, '>=', $filterDate['start'] . ' 00:00:00');
                }
                if (isset($filterDate['end']) && $filterDate['end'] !== '' && $filterDate['end'] !== null) {
                    $query->where($columnName, '<=', $filterDate['end'] . ' 23:59:59');
                }

                break;
            default:
                throw new \Exception('Invalid mode');
        }
    }

    private function applyMultipleValuesFilter(array $filterValue, Builder $query, string $columnName)
    {
        $filterValue = $this->clearArray($filterValue);

        if (count($filterValue) > 0) {
            $query->whereIn($columnName, $filterValue);
        }
    }

    private function clearArray($array)
    {
        foreach ($array as $idx => $value) {
            $value = trim($value);
            $array[$idx] = $value;
        }
        foreach ($array as $idx => $value) {
            if ($value === '' || $value === null) {
                unset($array[$idx]);
            }
        }
        return $array;
    }

    private function filterByPermissions(Builder $tasksQuery)
    {
        // Если пользователь является руководителем проектов A и B, то ограничить задачи, которые входят в проект A
        // или B
        $userId = Auth::id();
        $tasksQuery->where(function ($baseQuery) use ($userId) {
            $projects = DB::table('project_user')->where('user_id', $userId)->pluck('project_id')->toArray();
//        $projects = Project::where('heads', $userId)->select('id')->get()->toArray();
//        $projectsId = array_map(fn($project)=>$project['id'],$projects);
            if (count($projects) > 0) {
                $baseQuery->orWhereIn('tasks.id', function ($query) use ($projects) {
                    $query->select('task_id')
                        ->from('task_project')
                        ->whereIn('project_id', $projects);
                });
            }
            // Если пользователь является руководителем направлений A и B, то ограничить задачи, которые входят в
            // направление A или B
            $directionsId = Direction::where('head_id', $userId)
                ->select('id')
                ->get()
                ->map(fn($direction) => $direction['id'])
                ->toArray();

            if (count($directionsId) > 0) {
                $baseQuery->orWhereIn('users.direction_id', $directionsId);
            }

            // Если пользователь является руководителем группы A и B, то ограничить задачи, которые входят в
            // направление в котором находятся группы /группа
            $groupsDirections = Group::where('head_id', $userId)
                ->distinct()
                ->select('direction_id')
                ->get()
                ->map(fn($group) => $group['direction_id'])
                ->toArray();

            if (count($groupsDirections) > 0) {
                $baseQuery->orWhereIn('users.direction_id', $groupsDirections);
            }

            // Если пользователь является руководителем подгруппы A и B, то ограничить задачи, которые входят в
            // направление в котором находятся подгруппы /подгруппа

            $subgroupsDirections = Subgroup::where('subgroups.head_id', $userId)
                ->leftJoin('groups', 'subgroups.group_id', '=', 'groups.id')
                ->distinct()
                ->select('groups.direction_id')
                ->get()
                ->map(fn($subgroup) => $subgroup['direction_id'])
                ->toArray();

            if (count($subgroupsDirections) > 0) {
                $baseQuery->orWhereIn('users.direction_id', $subgroupsDirections);
            }

            // Если пользователь является руководителем семейства, то ограничить задачи, которые входят в
            // семейство
            $familyHeads =  DB::table('family_heads')->where('user_id', $userId)->pluck('family_id')->toArray();

            if (count($familyHeads) > 0) {
                $baseQuery->orWhereIn('tasks.id', function ($query) use ($familyHeads) {
                    $query->select('task_id')
                        ->from('task_family')
                        ->whereIn('family_id', $familyHeads);
                });
            }

            // Если пользователь является руководителем продукта, то ограничить задачи, которые входят в
            // продукт
//            $productHeads = Product::query()
//                ->where('head_id', $userId)
//                ->select('products.id')
//                ->get()
//                ->map(fn($product) => $product['id'])
//                ->toArray();
            $productHeads =  DB::table('product_heads')->where('user_id', $userId)->pluck('product_id')->toArray();;


            if (count($productHeads) > 0) {
                $baseQuery->orWhereIn('tasks.id', function ($query) use ($productHeads) {
                    $query->select('task_id')
                        ->from('task_product')
                        ->whereIn('product_id', $productHeads);
                });
            }

            // в противном случае ограничить задачи по ответственному
            $baseQuery->orWhere('user_id', $userId);
            if ($this->planerService->userIsPlaner($userId)) {
                $baseQuery->orWhereRaw('1=1');
            }


        });

    }

}
