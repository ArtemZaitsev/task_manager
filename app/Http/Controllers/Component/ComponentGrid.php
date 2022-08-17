<?php

namespace App\Http\Controllers\Component;

use App\BuisinessLogick\ComponentVoter;
use App\Http\Controllers\Component\Filter\DateFilter;
use App\Http\Controllers\Component\Filter\IntegerFilter;
use App\Http\Controllers\Component\Filter\MultiSelectFilter;
use App\Http\Controllers\Component\Filter\StringFilter;
use App\Http\Controllers\PhysicalObject\PhysicalObjectReportController;
use App\Lib\Grid\AbstractGrid;
use App\Lib\Grid\GridColumn;
use App\Lib\SelectUtils;
use App\Models\Component\Component;
use App\Models\Component\Component3dStatus;
use App\Models\Component\ComponentCalcStatus;
use App\Models\Component\ComponentDdStatus;
use App\Models\Component\ComponentManufactorStartWay;
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

class ComponentGrid extends AbstractGrid
{

    public function __construct(
        private ComponentVoter $componentVoter
    )
    {
        parent::__construct('components');

        $userSelectFilterData = SelectUtils::entityListToLabelMap(
            User::all()->all(),
            fn(User $entity) => $entity->label()
        );

        $this->columns = [
            new GridColumn(
                'actions',
                'Действия',
                fn(Component $entity) => view('lib.buttons.buttons', [
                    'buttons' => [
                        $this->componentVoter->canEditOrDelete($entity) ? [
                            'template' => 'lib.buttons.edit_button',
                            'templateData' => [
                                'url' => route(ComponentEditController::EDIT_ACTION, [
                                    'id' => $entity->id,
                                    'back' => url()->full()
                                ])
                            ]
                        ] : null,
                        $this->componentVoter->canEditOrDelete($entity) ? [
                            'template' => 'lib.buttons.delete_button',
                            'templateData' => [
                                'url' => route(ComponentDeleteController::ROUTE_NAME, [
                                    'id' => $entity->id,
                                    'back' => url()->full()
                                ])
                            ]
                        ] : null
                    ]
                ])->toHtml(),

                null,
                null,
                true,
                false
            ),

            new GridColumn(
                'created_at',
                'Дата создания',
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->created_at),
                'created_at',
                new DateFilter('created_at'),
                false
            ),
            new GridColumn(
                'updated_at',
                'Дата редактирования',
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->updated_at),
                'updated_at',
                new DateFilter('updated_at'),
                false
            ),
            new GridColumn(
                'physical_object_id',
                'Объект',
                fn(Component $entity) => $entity->physicalObject === null ? '' :
                    sprintf('<a href="%s" target="_blank">%s</a>',
                        route(PhysicalObjectReportController::ROUTE_NAME,
                            ['id' => $entity->physical_object_id]),
                        $entity->physicalObject?->label()),
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
                fn(Component $entity) => $entity->quantity_in_object === 0 ? '' : $entity->quantity_in_object,
                'quantity_in_object',
                new IntegerFilter('quantity_in_object')
            ),

            new GridColumn(
                'relative_component_id',
                'Верхнеуровневый компонент',
                fn(Component $entity) => $entity->relativeComponent?->label(),
                null,
                new MultiSelectFilter('relative_component_id',
                    SelectUtils::entityListToLabelMap(
                        Component::query()->where('is_highlevel', 1)->get()->all(),
                        fn(Component $c) => $c->label())
                )
            ),
            new GridColumn(
                'direction',
                'Направление',
                fn(Component $entity) => $entity->constructor?->direction?->label(),
                null,
                new MultiSelectFilter('users.direction_id',
                    SelectUtils::entityListToLabelMap(
                        Direction::all()->all(),
                        fn(Direction $o) => $o->label())
                )
            ),
            new GridColumn(
                'title',
                'Название компонента',
                fn(Component $entity) => $entity->title,
                'title',
                new StringFilter('title')
            ),
            new GridColumn(
                'identifier',
                'Идентификатор компонента',
                fn(Component $entity) => $entity->identifier,
                null,
                new StringFilter('identifier')
            ),
            new GridColumn(
                'entry_level',
                'Уровень входимости',
                fn(Component $entity) => $entity->entry_level === 0 ? '' : $entity->entry_level,
                null,
                new IntegerFilter('entry_level')
            ),
            new GridColumn(
                'source_type',
                'Как получаем',
                fn(Component $entity) => ComponentSourceType::LABELS[$entity->source_type] ?? '',
                'source_type',
                new MultiSelectFilter('source_type', $this->nullValue(ComponentSourceType::LABELS))
            ),
            new GridColumn(
                'version',
                'Версия',
                fn(Component $entity) => ComponentVersion::LABELS[$entity->version] ?? '',
                'version',
                new MultiSelectFilter('version', $this->nullValue(ComponentVersion::LABELS))
            ),
            new GridColumn(
                'type',
                'Тип компонента',
                fn(Component $entity) => ComponentType::LABELS[$entity->type] ?? '',
                'type',
                new MultiSelectFilter('type', $this->nullValue(ComponentType::LABELS))
            ),
            new GridColumn(
                'manufactor_start_way',
                'Способ запуска в производство',
                fn(Component $entity) => ComponentManufactorStartWay::LABELS[$entity->manufactor_start_way] ?? '',
                'manufactor_start_way',
                new MultiSelectFilter('manufactor_start_way', $this->nullValue(ComponentManufactorStartWay::LABELS))
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
                new MultiSelectFilter('3d_status', $this->nullValue(Component3dStatus::LABELS))
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
                new MultiSelectFilter('dd_status', $this->nullValue(ComponentDdStatus::LABELS))
            ),
            new GridColumn(
                'dd_date_plan',
                'Планируемая дата выдачи КД',
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->dd_date_plan),
                'dd_date_plan',
                new DateFilter('dd_date_plan'),
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
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->drawing_date),
                'drawing_date',
                new DateFilter('drawing_date'),
            ),
            new GridColumn(
                'calc_status',
                'Статус Расчетов',
                fn(Component $entity) => ComponentCalcStatus::LABELS[$entity->calc_status] ?? '',
                'calc_status',
                new MultiSelectFilter('calc_status', $this->nullValue(ComponentCalcStatus::LABELS))
            ),
            new GridColumn(
                'calc_date_plan',
                'Планируемая дата завершения расчетов',
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->calc_date_plan),
                'calc_date_plan',
                new DateFilter('calc_date_plan'),
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
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->tz_date),
                'tz_date',
                new DateFilter('tz_date'),
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
                fn(Component $entity) => $entity->constructor_comment,
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
                new MultiSelectFilter('manufactor_status', $this->nullValue(ComponentManufactorStatus::LABELS))
            ),
            new GridColumn(
                'manufactor_date_plan',
                'Планируемая дата производства',
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->getAttribute('manufactor_date_plan')),
                'manufactor_date_plan',
                new DateFilter('manufactor_date_plan'),
            ),
            new GridColumn(
                'sz',
                'СЗ (ссылка)',
                fn(Component $entity) => $entity->sz === null ? '' :
                    sprintf('<a href="%s" target="_blank">%s</a>',
                        '/files/' . $entity->sz->file_path,
                        $entity->sz->label()
                    )

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
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->manufactor_sz_date),
                'manufactor_sz_date',
                new DateFilter('manufactor_sz_date'),
            ),
            new GridColumn(
                'manufactor_sz_quantity',
                'Количество в СЗ',
                fn(Component $entity) => $entity->manufactor_sz_quantity === 0 ? '' : $entity->manufactor_sz_quantity,
                null,
                new IntegerFilter('manufactor_sz_quantity')
            ),
            new GridColumn(
                'manufactor_priority',
                'Приоритет производства',
                fn(Component $entity) => $entity->manufactor_priority === 0 ? '' : $entity->manufactor_priority,
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
                new MultiSelectFilter('purchase_status', $this->nullValue(ComponentPurchaserStatus::LABELS))
            ),
            new GridColumn(
                'purchase_date_plan',
                'Планируемая дата поставки',
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->purchase_date_plan),
                'purchase_date_plan',
                new DateFilter('purchase_date_plan'),
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
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->purchase_request_date),
                'purchase_request_date',
                new DateFilter('purchase_request_date'),
            ),
            new GridColumn(
                'purchase_request_quantity',
                'Количество в заявке',
                fn(Component $entity) => $entity->purchase_request_quantity === 0 ? '' : $entity->purchase_request_quantity,
                null,
                new IntegerFilter('purchase_request_quantity')
            ),
            new GridColumn(
                'purchase_request_priority',
                'Приоритет закупщика',
                fn(Component $entity) => $entity->purchase_request_priority === 0 ? '' : $entity->purchase_request_priority,
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
        ];
    }

    public function buildQuery(Request $request): Builder
    {
        $query = Component::query()
            ->leftJoin('users', 'users.id', '=', 'components.constructor_id')
            //->leftJoin('directions', '', '=', 'directions.id');
            ->select('components.*');
        $this->applyFilters($query, $request);
        $this->applySort($query, $request);

        return $query;
    }

    private function nullValue(array $data): array
    {
        return array_merge([0 => 'Не указано'], $data);
    }
}
