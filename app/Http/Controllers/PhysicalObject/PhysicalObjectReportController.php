<?php

namespace App\Http\Controllers\PhysicalObject;

use App\Http\Controllers\Component\ComponentController;
use App\Http\Controllers\Component\Filter\MultiSelectFilter;
use App\Http\Controllers\Component\SelectUtils;
use App\Http\Controllers\Controller;
use App\Models\Component\Component;
use App\Models\Component\Component3dStatus;
use App\Models\Component\ComponentCalcStatus;
use App\Models\Component\ComponentDdStatus;
use App\Models\Component\ComponentManufactorStatus;
use App\Models\Component\ComponentPurchaserStatus;
use App\Models\Component\PhysicalObject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PhysicalObjectReportController extends Controller
{
    public const ROUTE_NAME = 'physical_object.report';

    public function index(Request $request, $id)
    {
        $object = PhysicalObject::query()->findOrFail($id);

        $highLevelComponentsAll = Component::query()
            ->where('physical_object_id', $object->id)
            ->where('is_highlevel', 1)
            ->get()
            ->all();

        $highLevelComponents = $this->filterComponents($highLevelComponentsAll, $request);
        usort($highLevelComponents, fn(Component $a, Component $b) => $a->constructor?->direction?->title <=> $b->constructor?->direction?->title);

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
                '3d_status' => '3D-моделей',
                'dd_status' => 'КД',
                'calc_status' => 'в расчетах',
                'manufactor_status' => 'в изготовлении',
                'purchase_status' => 'в закупках'
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
                // remove NOT_REQUIRED status for all reports
                unset($copyReport[ComponentManufactorStatus::NOT_REQUIRED]);
                if($status === 'manufactor_status') {
                    unset($copyReport[ComponentManufactorStatus::DD_NOT_TRANSMITTED]);
                    unset($copyReport[ComponentManufactorStatus::DD_TECHNICAL_APPROVAL]);
                }
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
            'filters' => [
                'component' => new MultiSelectFilter('component_id', SelectUtils::entityListToLabelMap(
                    $highLevelComponentsAll,
                    fn(Component $entity) => $entity->label()
                ))
            ],
            'filterUrl' => fn($componentId, array $additionalParams = []) => route(
                ComponentController::ROUTE_NAME, [
                'filters' => array_merge([
                    'physical_object_id' => [$report['object']->id],
                    'relative_component_id' => [$componentId]
                ], $additionalParams)
            ]),
            'totalFilterUrl' => fn(string $statusField, int $statusValue) => route(ComponentController::ROUTE_NAME, [
                'filters' => [
                    'physical_object_id' => [$object->id],
                    $statusField => [$statusValue]
                ]
            ]),
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

    private function filterComponents(array $highLevelComponentsAll, Request $request): array
    {
        if (!$request->query->has('filters')) {
            return $highLevelComponentsAll;
        }
        $filters = $request->query->get('filters');
        if (!isset($filters['component_id'])) {
            return $highLevelComponentsAll;
        }

        $filterValue = (array)$filters['component_id'];
        if (empty($filterValue)) {
            return $highLevelComponentsAll;
        }

        $filterValue = array_map(fn($value) => (int) $value, $filterValue);


        $highLevelComponentsAll = array_filter($highLevelComponentsAll,
            fn(Component $component) => in_array($component->id, $filterValue));
        return $highLevelComponentsAll;

    }
}
