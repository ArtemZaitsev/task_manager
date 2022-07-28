<?php

namespace App\Http\Controllers\Component;

use App\Http\Controllers\Component\Filter\IntegerFilter;
use App\Http\Controllers\Component\Filter\MultiSelectFilter;
use App\Http\Controllers\Component\Filter\StringFilter;
use App\Models\Component\Component;
use App\Models\Component\Component3dStatus;
use App\Models\Component\ComponentCalcStatus;
use App\Models\Component\ComponentDdStatus;
use App\Models\Component\ComponentManufactorStatus;
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
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->getAttribute('created_at'))
                ),
                new GridColumn(
                    'updated_at',
                    'Дата редактирования',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->getAttribute('updated_at'))
                ),
                new GridColumn(
                    'physical_objects',
                    'Объекты',
                    fn(Component $entity) => implode('<br/>', array_map(fn(PhysicalObject $po) => $po->label(),
                        $entity->physicalObjects->all()))
                ),
                new GridColumn(
                    'relative_component',
                    'Род компонент',
                    fn(Component $entity) => $entity->relativeComponent?->label()
                ),
                new GridColumn(
                    'direction',
                    'Направление',
                    fn(Component $entity) => $entity->constructor?->direction?->label()
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
                    'Дата планируемая',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->getAttribute('3d_date_plan'))
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
                    'Дата планируемая',
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
                    'drawing_date_plan',
                    'Дата чертежей',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->drawing_date_plan)
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
                    'Дата планируемая',
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
                    'tz_date_plan',
                    'Дата ТЗ',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->tz_date_plane)
                ),
                new GridColumn(
                    'constructor_priority',
                    'Приоритет',
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
                    'Дата планируемая',
                    fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->getAttribute('manufactor_date_plan'))
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
