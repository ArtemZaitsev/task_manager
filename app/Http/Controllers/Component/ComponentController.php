<?php

namespace App\Http\Controllers\Component;

use App\BuisinessLogick\PlanerService;
use App\BuisinessLogick\ProjectVoter;
use App\BuisinessLogick\TaskService;
use App\BuisinessLogick\TaskVoter;
use App\Http\Controllers\Component\Filter\DateFilter;
use App\Http\Controllers\Component\Filter\IntegerFilter;
use App\Http\Controllers\Component\Filter\MultiSelectFilter;
use App\Http\Controllers\Component\Filter\StringFilter;
use App\Http\Controllers\PhysicalObject\PhysicalObjectReportController;
use App\Http\Controllers\Task\TasksExportController;
use App\Models\Component\Component;
use App\Models\Component\Component3dStatus;
use App\Models\Component\ComponentCalcStatus;
use App\Models\Component\ComponentDdStatus;
use App\Models\Component\ComponentManufactorStatus;
use App\Models\Component\ComponentPurchaserStatus;
use App\Models\Component\ComponentSourceType;
use App\Models\Component\ComponentType;
use App\Models\Component\ComponentVersion;
use App\Models\Component\PhysicalObject;
use App\Models\Direction;
use App\Models\User;
use App\Utils\DateUtils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\InputBag;

class ComponentController
{
    public const ROUTE_NAME = 'components.list';
    const RECORDS_PER_PAGE = 10;

    public function __construct(
        private TaskVoter     $taskVoter,
        private TaskService   $taskService,
        private PlanerService $planerService,
        private ProjectVoter  $projectVoter,
    )
    {

    }

    public function index(Request $request)
    {
        $grid = new ComponentGrid();
        $query = $grid->buildQuery($request);

        $components = $query->paginate(self::RECORDS_PER_PAGE);


        return view('component.list', [
            'data' => $components,
            'columns' => $grid->getColumns(),
            'taskVoter' => $this->taskVoter,
            'projectVoter' => $this->projectVoter,
            'taskService' => $this->taskService,
            'planerService' => $this->planerService,
            'exportUrl' => route(ComponentExportController::EXPORT_ACTION) . '?' . http_build_query
                ($request->query->all()),
        ]);
    }


}
