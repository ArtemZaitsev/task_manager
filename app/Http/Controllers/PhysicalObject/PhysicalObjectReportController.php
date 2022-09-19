<?php

namespace App\Http\Controllers\PhysicalObject;

use App\Http\Controllers\Component\ComponentController;
use App\Http\Controllers\Component\Filter\Filter;
use App\Http\Controllers\Component\Filter\MultiSelectFilter;
use App\Lib\Grid\Field\Field;
use App\Lib\Grid\Field\FieldSet;
use App\Lib\SelectUtils;
use App\Http\Controllers\Controller;
use App\Models\Component\Component;
use App\Models\Component\Component3dStatus;
use App\Models\Component\ComponentCalcStatus;
use App\Models\Component\ComponentDdStatus;
use App\Models\Component\ComponentManufactorStatus;
use App\Models\Component\ComponentPurchaserStatus;
use App\Models\Component\Metasystem;
use App\Models\Component\PhysicalObject;
use App\Models\Component\Subsystem;
use App\Models\Component\System;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PhysicalObjectReportController extends Controller
{
    public const ROUTE_NAME = 'physical_object.report';
    public const SAVE_FIELDS_ROUTE_NAME = 'physical_object.save_fields';

    const STATUS_3d = '3d_status';
    const DD_STATUS = 'dd_status';
    const CALC_STATUS = 'calc_status';
    const MANUFACTOR_STATUS = 'manufactor_status';
    const PURCHASE_STATUS = 'purchase_status';

    const STATUS_FIELD_LABELS = [
        self::STATUS_3d => '3D модели',
        self::DD_STATUS => 'Чертежи',
        self::CALC_STATUS => 'Расчеты',
        self::MANUFACTOR_STATUS => 'Производство',
        self::PURCHASE_STATUS => 'Закупки',
    ];

    public function index(Request $request, $id)
    {
        /** @var PhysicalObject $object */
        $object = PhysicalObject::query()->findOrFail($id);

        $highLevelComponentsAll = Component::query()
            ->where('physical_object_id', $object->id)
            ->where('is_highlevel', 1)
            ->get()
            ->all();

        $filters =  [
            'component' => new MultiSelectFilter('id', SelectUtils::entityListToLabelMap(
                $highLevelComponentsAll,
                fn(Component $entity) => $entity->label()
            )),
            'metasystem' => new MultiSelectFilter('metasystem_id', SelectUtils::entityListToLabelMap(
                Metasystem::all()->all(),
                fn(Metasystem $entity) => $entity->label()
            )),
            'system' => new MultiSelectFilter('system_id', SelectUtils::entityListToLabelMap(
                System::all()->all(),
                fn(System $entity) => $entity->label()
            )),
            'subsystem' => new MultiSelectFilter('subsystem_id', SelectUtils::entityListToLabelMap(
                Subsystem::all()->all(),
                fn(Subsystem $entity) => $entity->label()
            ))
        ];

        $highLevelComponents = $this->filterComponents($request, $object, $filters);
        usort($highLevelComponents, fn(Component $a, Component $b) => $a->constructor?->direction?->title <=> $b->constructor?->direction?->title);

        $report = [
            'object' => $object,
            'status' => [
                self::STATUS_3d => Component3dStatus::LABELS,
                self::DD_STATUS => ComponentDdStatus::LABELS,
                self::CALC_STATUS => ComponentCalcStatus::LABELS,
                self::MANUFACTOR_STATUS => ComponentManufactorStatus::LABELS,
                self::PURCHASE_STATUS => ComponentPurchaserStatus::LABELS
            ],
            'status_titles' => [
                self::STATUS_3d => '3D-моделей',
                self::DD_STATUS => 'чертежей',
                self::CALC_STATUS => 'в расчетах',
                self::MANUFACTOR_STATUS => 'в изготовлении',
                self::PURCHASE_STATUS => 'в закупках'
            ],
            'rows' => [],
            'totalStatusWithoutNotRequired' => [],
            'footer' => []
        ];


        /** @var Component $component */
        foreach ($highLevelComponents as $component) {
            $fields = [self::STATUS_3d, self::DD_STATUS, self::CALC_STATUS, self::MANUFACTOR_STATUS, self::PURCHASE_STATUS];
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
                $copyReport = $this->clearTotalStatStatuses($statusReport, $status);
                if (!isset($report['totalStatusWithoutNotRequired'][$status])) {
                    $report['totalStatusWithoutNotRequired'][$status]['count'] = 0;
                }
                $report['totalStatusWithoutNotRequired'][$status]['count'] += array_sum($copyReport);
            }
        }

        foreach ($report['status'] as $status => $statusValues) {
            $clearedStatuses = array_keys($this->clearTotalStatStatuses($statusValues, $status));
            $report['totalStatusWithoutNotRequired'][$status]['url'] = route(ComponentController::ROUTE_NAME, [
                'filters' => array_merge($this->baseFilters($object, $highLevelComponents, $highLevelComponentsAll), [
                    $status => $clearedStatuses
                ])
            ]);
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


        $fieldSet = $this->buildFieldSet();
        $fieldSet->load();

        return view('physical_object.report', [
            'report' => $report,
            'fieldSet' => $fieldSet,
            'filters' => $filters,
            'filterUrl' => fn($componentId, array $additionalParams = []) => route(
                ComponentController::ROUTE_NAME, [
                'filters' => array_merge([
                    'physical_object_id' => [$report['object']->id],
                    'relative_component_id' => [$componentId]
                ], $additionalParams)
            ]),
            'totalFilterUrl' => function (string $statusField, int $statusValue = null) use (
                $highLevelComponentsAll,
                $request, $highLevelComponents, $object
            ) {
                $filters = $this->baseFilters($object, $highLevelComponents, $highLevelComponentsAll);
                if ($statusValue !== null) {
                    $filters[$statusField] = [$statusValue];
                }
                return route(ComponentController::ROUTE_NAME, [
                    'filters' => $filters
                ]);
            },
            'totalUrl' => route(ComponentController::ROUTE_NAME, [
                'filters' => $this->baseFilters($report['object'], $highLevelComponents, $highLevelComponentsAll)
            ]),

        ]);
    }

    public function saveDisplayFields(Request $request)
    {
        $requestFields = array_keys($request->request->get('fields'));
        $fieldSet = $this->buildFieldSet();
        $fieldSet->save($requestFields);


        return response()->json([
            'success' => true
        ]);
    }

    private function buildFieldSet(): FieldSet
    {
        $fields = [
            self::STATUS_3d,
            self::DD_STATUS,
            self::CALC_STATUS,
            self::MANUFACTOR_STATUS,
            self::PURCHASE_STATUS
        ];
        return new FieldSet(
            array_map(
                fn(string $field) => new Field($field, self::STATUS_FIELD_LABELS[$field], true),
                $fields
            ),
            'phys_obj_report'
        );
    }

    private function clearTotalStatStatuses(array $statuses, string $status): array
    {
        unset($statuses[0]);
        unset($statuses[ComponentManufactorStatus::NOT_REQUIRED]);
        $statusesToClear = [
            self::MANUFACTOR_STATUS => [ComponentManufactorStatus::DD_NOT_TRANSMITTED,
                ComponentManufactorStatus::HAVE_NOT_SZ],
            self::PURCHASE_STATUS => [ComponentPurchaserStatus::DOES_NOT_DONE],
        ];
        foreach ($statusesToClear as $statusToClear => $values) {
            if ($statusToClear === $status) {
                foreach ($values as $value) {
                    unset($statuses[$value]);
                }
            }
        }

        return $statuses;
    }

    private function baseFilters(PhysicalObject $object, array $highLevelComponents, array $highLevelComponentsAll): array
    {
        $filters = ['physical_object_id' => [$object->id]];

        if (count($highLevelComponents) !== count($highLevelComponentsAll)) {
            $filters['relative_component_id'] = array_map(fn(Component $entity) => $entity->id,
                $highLevelComponents);
        }
        return $filters;
    }

    private
    function fieldReport(Component $component, string $fieldName): array
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

    private
    function totalComponents(Component $component): int
    {
        $result = Component::query()
            ->where('physical_object_id', $component->physical_object_id)
            ->where('relative_component_id', $component->id)
            ->select(DB::raw('COUNT(*) as component_count'))
            ->get()
            ->toArray();
        return (int)$result[0]['component_count'];
    }

    private
    function filterComponents(Request $request, PhysicalObject $object, array $filters): array
    {
        $query = Component::query()
            ->where('physical_object_id', $object->id)
            ->where('is_highlevel', 1);

        if ($request->query->has('filters')) {
            $filtersData= $request->query->get('filters');
            foreach ($filters as $filter) {
                /** @var Filter $filter */
                $filterData = $filtersData[$filter->name()] ?? null;
                if($filterData !== null) {
                    $filter->apply($query, $filterData);
                }
            }
        }

        $components = $query
            ->get()
            ->all();

        return $components;
    }
}
