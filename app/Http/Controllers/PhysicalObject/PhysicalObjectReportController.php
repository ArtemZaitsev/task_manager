<?php

namespace App\Http\Controllers\PhysicalObject;

use App\Http\Controllers\Component\ComponentController;
use App\Http\Controllers\Controller;
use App\Models\Component\Component;
use App\Models\Component\Component3dStatus;
use App\Models\Component\ComponentCalcStatus;
use App\Models\Component\ComponentDdStatus;
use App\Models\Component\ComponentManufactorStatus;
use App\Models\Component\ComponentPurchaserStatus;
use App\Models\Component\PhysicalObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PhysicalObjectReportController extends Controller
{
    public const ROUTE_NAME = 'physical_object.report';

    public function index(Request $request, $id)
    {
        $object = PhysicalObject::query()->findOrFail($id);

        $highLevelComponents = Component::query()
            ->where('physical_object_id', $object->id)
            ->where('is_highlevel', 1)
            ->get()
            ->all();
        $report = [
            'object' => $object,
            'status' => [
                '3d_status' => Component3dStatus::LABELS,
                'dd_status' => ComponentDdStatus::LABELS,
                'calc_status' => ComponentCalcStatus::LABELS,
                'manufactor_status' => ComponentManufactorStatus::LABELS,
                'purchase_status' => ComponentPurchaserStatus::LABELS
            ],
            'status_titles' => [
                '3d_status' => '3D модели',
                'dd_status' => 'КД',
                'calc_status' => 'Расчеты',
                'manufactor_status' => 'Производство',
                'purchase_status' => 'Закупки'
            ],
            'rows' => [],
            'totalStatusWithoutNotRequired' => [],
            'footer' => []
        ];


        /** @var Component $component */
        foreach ($highLevelComponents as $component) {
            $fields = ['3d_status', 'dd_status', 'calc_status', 'manufactor_status', 'purchase_status'];
            $report['rows'][$component->id] = [
                'component' => $component,
                'total' => 0,
                'byStatus' => []
            ];
            foreach ($fields as $field) {
                $fieldReport = $this->fieldReport($component, $field);
                $report['rows'][$component->id]['total'] = $this->totalComponents($component);
                $report['rows'][$component->id]['byStatus'][$field] = $fieldReport;
            }

        }

        foreach ($report['rows'] as $componentReport) {
            foreach ($componentReport['byStatus'] as $status => $statusReport) {
                $copyReport = $statusReport;
                // remome NOT_REQUIRED status
                unset($copyReport[1]);
                if (!isset($report['totalStatusWithoutNotRequired'][$status])) {
                    $report['totalStatusWithoutNotRequired'][$status] = 0;
                }
                $report['totalStatusWithoutNotRequired'][$status] += array_sum($copyReport);

            }
        }

        $report['footer']['total'] = array_sum(
            array_map(fn(array $componentReport) => $componentReport['total'], $report['rows'])
        );

        $totalStatusSum = [];
        foreach ($report['rows'] as $componentReport) {
            foreach ($componentReport['byStatus'] as $field => $statusReport) {
                foreach ($statusReport as $status => $itemsCount) {
                    if (!isset($totalStatusSum[$field][$status])) {
                        $totalStatusSum[$field][$status] = 0;
                    }
                    $totalStatusSum[$field][$status] += $itemsCount;
                }
            }
        }
        $report['footer']['status'] = $totalStatusSum;


        return view('physical_object.report', [
            'report' => $report,
            'filterUrl' => fn($componentId, array $additionalParams = []) => route(
                ComponentController::ROUTE_NAME, [
                'filters' => array_merge([
                    'physical_object_id' => [$report['object']->id],
                    'relative_component_id' => [$componentId]
                ], $additionalParams)
            ])
        ]);
    }

    private function fieldReport(Component $component, string $fieldName): array
    {
        $groupResult = Component::query()
            ->where('physical_object_id', $component->physical_object_id)
            ->where('relative_component_id', $component->id)
            ->select(DB::raw('COUNT(*) as status_count'), $fieldName)
            ->groupBy($fieldName)
            ->get()
            ->toArray();

        $resultMap = [];
        foreach ($groupResult as $row) {
            if ($row[$fieldName] === null) {
                $resultMap[0] = $row['status_count'];
            } else {
                $resultMap[$row[$fieldName]] = $row['status_count'];
            }
        }

        return $resultMap;

    }

    private function totalComponents(Component $component): int
    {
        $result = Component::query()
            ->where('physical_object_id', $component->physical_object_id)
            ->where('relative_component_id', $component->id)
            ->select(DB::raw('COUNT(*) as component_count'))
            ->get()
            ->toArray();
        return (int)$result[0]['component_count'];
    }
}
