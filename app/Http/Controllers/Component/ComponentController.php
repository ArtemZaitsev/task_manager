<?php

namespace App\Http\Controllers\Component;

use App\Http\Controllers\Component\Filter\DateFilter;
use App\Http\Controllers\Component\Filter\IntegerFilter;
use App\Http\Controllers\Component\Filter\MultiSelectFilter;
use App\Http\Controllers\Component\Filter\StringFilter;
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
use App\Models\User;
use App\Utils\DateUtils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ComponentController
{
    public const ROUTE_NAME = 'components.list';

    public function index(Request $request)
    {
        $userSelectFilterData = $this->buildUserFilterData();

        $grid = [
            'columns' => [
                new GridColumn(
                    'id',
                    'ID',
                    fn(Component $entity) => $entity->id,
                    'id'
                ),
                new GridColumn(
                    'created_at',
                    'Дата создания',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->created_at)
                ),
                new GridColumn(
                    'updated_at',
                    'Дата редактирования',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->updated_at)
                ),
                new GridColumn(
                    'physical_object_id',
                    'Объект',
                    fn(Component $entity) => $entity->physicalObject?->label(),
                    null,
                    new MultiSelectFilter('physical_object_id',
                        SelectUtils::entityListToLabelMap(
                            PhysicalObject::all()->all(),
                            fn(PhysicalObject $o) => $o->label())
                    )
                ),

                new GridColumn(
                    'quantity_in_object',
                    'Количество в объекте',
                    fn(Component $entity) => $entity->quantity_in_object,
                    'quantity_in_object',
                    new IntegerFilter('quantity_in_object')
                ),

                new GridColumn(
                    'relative_component_id',
                    'Родительский компонент',
                    fn(Component $entity) => $entity->relativeComponent?->label(),
                    null,
                    new MultiSelectFilter('relative_component_id',
                        SelectUtils::entityListToLabelMap(
                            Component::all()->all(),
                            fn(Component $c) => $c->label())
                    )
                ),
                new GridColumn(
                    'direction',
                    'Направление',
                    fn(Component $entity) => $entity->constructor?->direction?->label(),
                    null,
                ),
                new GridColumn(
                    'title',
                    'Название',
                    fn(Component $entity) => $entity->title,
                    'title',
                    new StringFilter('title')
                ),
                new GridColumn(
                    'identifier',
                    'Идентификатор',
                    fn(Component $entity) => $entity->identifier,
                    null,
                    new StringFilter('identifier')
                ),
                new GridColumn(
                    'entry_level',
                    'Уровень входимости',
                    fn(Component $entity) => $entity->entry_level,
                    null,
                    new IntegerFilter('entry_level')
                ),
                new GridColumn(
                    'source_type',
                    'Как получаем',
                    fn(Component $entity) => ComponentSourceType::LABELS[$entity->source_type] ?? '',
                    'source_type',
                    new MultiSelectFilter('source_type', ComponentSourceType::LABELS)
                ),
                new GridColumn(
                    'version',
                    'Версия',
                    fn(Component $entity) => ComponentVersion::LABELS[$entity->version] ?? '',
                    'version',
                    new MultiSelectFilter('version', ComponentVersion::LABELS)
                ),
                new GridColumn(
                    'type',
                    'Тип компонента',
                    fn(Component $entity) => ComponentType::LABELS[$entity->type] ?? '',
                    'type',
                    new MultiSelectFilter('type', ComponentType::LABELS)
                ),
                new GridColumn(
                    'constructor_id',
                    'Конструктор',
                    fn(Component $entity) => $entity->constructor?->label(),
                    null,
                    new MultiSelectFilter('constructor_id', $userSelectFilterData)
                ),
                new GridColumn(
                    '3d_status',
                    'Статус 3D-модели',
                    fn(Component $entity) => Component3dStatus::LABELS[$entity->getAttribute('3d_status')] ?? '',
                    '3d_status',
                    new MultiSelectFilter('3d_status', Component3dStatus::LABELS)
                ),
                new GridColumn(
                    '3d_date_plan',
                    'Планируемая дата подготовки 3D',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->getAttribute('3d_date_plan')),
                    '3d_date_plan',
                    new DateFilter('3d_date_plan')
                ),
                new GridColumn(
                    'dd_status',
                    'Статус КД',
                    fn(Component $entity) => ComponentDdStatus::LABELS[$entity->dd_status] ?? '',
                    'dd_status',
                    new MultiSelectFilter('dd_status', ComponentDdStatus::LABELS)
                ),
                new GridColumn(
                    'dd_date_plan',
                    'Планируемая дата выдачи КД',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->dd_date_plan)
                ),
                new GridColumn(
                    'drawing_files',
                    'Чертежи',
                    fn(Component $entity) => $entity->drawing_files,
                    'drawing_files',
                    new StringFilter('drawing_files')
                ),
                new GridColumn(
                    'drawing_date',
                    'Дата чертежей',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->drawing_date)
                ),
                new GridColumn(
                    'calc_status',
                    'Статус Расчетов',
                    fn(Component $entity) => ComponentCalcStatus::LABELS[$entity->calc_status] ?? '',
                    'calc_status',
                    new MultiSelectFilter('calc_status', ComponentCalcStatus::LABELS)
                ),
                new GridColumn(
                    'calc_date_plan',
                    'Планируемая дата завершения расчетов',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->calc_date_plan)
                ),
                new GridColumn(
                    'tz_files',
                    'ТЗ',
                    fn(Component $entity) => $entity->tz_files,
                    'tz_files',
                    new StringFilter('tz_files')
                ),
                new GridColumn(
                    'tz_date',
                    'Дата ТЗ',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->tz_date)
                ),
                new GridColumn(
                    'constructor_priority',
                    'Приоритет конструктора',
                    fn(Component $entity) => $entity->constructor_priority,
                    null,
                    new IntegerFilter('constructor_priority')
                ),
                new GridColumn(
                    'constructor_comment',
                    'Примечание конструктора',
                    fn(Component $entity) => $entity->drawing_files,
                    'constructor_comment',
                    new StringFilter('constructor_comment')
                ),
                new GridColumn(
                    'manufactor_id',
                    'Ответственный ЗОК',
                    fn(Component $entity) => $entity->manufactor?->label(),
                    null,
                    new MultiSelectFilter('manufactor_id', $userSelectFilterData)
                ),
                new GridColumn(
                    'manufactor_status',
                    'Статус производства',
                    fn(Component $entity) => ComponentManufactorStatus::LABELS[$entity->manufactor_status] ?? '',
                    'manufactor_status',
                    new MultiSelectFilter('manufactor_status', ComponentManufactorStatus::LABELS)
                ),
                new GridColumn(
                    'manufactor_date_plan',
                    'Планируемая дата производства',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->getAttribute('manufactor_date_plan'))
                ),
                new GridColumn(
                    'manufactor_sz_files',
                    'СЗ',
                    fn(Component $entity) => $entity->manufactor_sz_files,
                    'manufactor_sz_files',
                    new StringFilter('manufactor_sz_files')
                ),
                new GridColumn(
                    'manufactor_sz_date',
                    'Дата СЗ',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->manufactor_sz_date)
                ),
                new GridColumn(
                    'manufactor_sz_quantity',
                    'Количество в СЗ',
                    fn(Component $entity) => $entity->manufactor_sz_quantity,
                    null,
                    new IntegerFilter('manufactor_sz_quantity')
                ),
                new GridColumn(
                    'manufactor_priority',
                    'Приоритет производства',
                    fn(Component $entity) => $entity->manufactor_priority,
                    null,
                    new IntegerFilter('manufactor_priority')
                ),
                new GridColumn(
                    'manufactor_comment',
                    'Примечание ответственного ЗОК',
                    fn(Component $entity) => $entity->manufactor_comment,
                    'manufactor_comment',
                    new StringFilter('manufactor_comment')
                ),
                new GridColumn(
                    'purchaser_id',
                    'Ответственный закупщик',
                    fn(Component $entity) => $entity->purchaser?->label(),
                    null,
                    new MultiSelectFilter('purchaser_id', $userSelectFilterData)
                ),
                new GridColumn(
                    'purchase_status',
                    'Статус закупки',
                    fn(Component $entity) => ComponentPurchaserStatus::LABELS[$entity->purchase_status] ?? '',
                    'purchase_status',
                    new MultiSelectFilter('purchase_status', ComponentPurchaserStatus::LABELS)
                ),
                new GridColumn(
                    'purchase_date_plan',
                    'Планируемая дата поставки',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->purchase_date_plan)
                ),
                new GridColumn(
                    'purchase_request_files',
                    'Заявка',
                    fn(Component $entity) => $entity->purchase_request_files,
                    'purchase_request_files',
                    new StringFilter('purchase_request_files')
                ),
                new GridColumn(
                    'purchase_request_date',
                    'Дата заявки',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->purchase_request_date)
                ),
                new GridColumn(
                    'purchase_request_quantity',
                    'Количество в заявке',
                    fn(Component $entity) => $entity->purchase_request_quantity,
                    null,
                    new IntegerFilter('purchase_request_quantity')
                ),
                new GridColumn(
                    'purchase_request_priority',
                    'Приоритет закупщика',
                    fn(Component $entity) => $entity->purchase_request_priority,
                    null,
                    new IntegerFilter('purchase_request_priority')
                ),
                new GridColumn(
                    'purchase_comment',
                    'Примечание закупщика',
                    fn(Component $entity) => $entity->purchase_comment,
                    'purchase_comment',
                    new StringFilter('purchase_comment')
                ),
            ]
        ];

        $query = Component::query();
        $this->applyFilters($query, $request, $grid);
        $this->applySort($query, $request, $grid);

        $components = $query->paginate();


        return view('component.list', [
            'data' => $components,
            'grid' => $grid
        ]);
    }

    private function applyFilters(Builder $query, Request $request, array $grid): void
    {
        if (!$request->has('filters')) {
            return;
        }

        $filters = [];
        foreach ($grid['columns'] as $column) {
            /** @var GridColumn $column */
            $filter = $column->getFilter();
            if ($filter !== null) {
                if (isset($filters[$filter->name()])) {
                    throw new \LogicException(sprintf('Filter woth name %s exist', $filter->name()));
                }
                $filters[$filter->name()] = $filter;
            }
        }

        $filtersData = $request->get('filters');
        foreach ($filtersData as $filterName => $filterData) {
            if (!isset($filters[$filterName])) {
                throw new \LogicException(sprintf('Filter %s not exist', $filterName));
            }
            $filter = $filters[$filterName];
            $filter->apply($query, $filterData);
        }
    }

    private function applySort(Builder $query, Request $request, array $grid): void
    {
        if (!$request->query->has('sort')) {
            return;
        }
        $orderField = $request->query->get('sort');
        if (empty($orderField)) {
            return;
        }
        $orderDirection = $request->query->get('sort_direction') ?? 'ASC';
        $query->orderBy($orderField, $orderDirection);
    }

    private function buildUserFilterData(): array
    {
        /** @var User[] $users */
        $users = User::all();

        $filterData = [];
        foreach ($users as $user) {
            $filterData[$user->id] = $user->label();
        }

        return $filterData;
    }

}
