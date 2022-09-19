<?php

namespace App\Http\Controllers\Component;

use App\BuisinessLogick\Voter\ComponentVoter;
use App\Http\Controllers\Component\Filter\DateFilter;
use App\Http\Controllers\Component\Filter\IntegerFilter;
use App\Http\Controllers\Component\Filter\MultiSelectFilter;
use App\Http\Controllers\Component\Filter\StringFilter;
use App\Http\Controllers\PhysicalObject\PhysicalObjectReportController;
use App\Http\Controllers\Sz\SzFileDownloadController;
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
use App\Models\Component\Metasystem;
use App\Models\Component\PhysicalObject;
use App\Models\Component\Subsystem;
use App\Models\Component\System;
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
    }

    protected function buildColumns(): array
    {
        $userSelectFilterData = SelectUtils::entityListToLabelMap(
            User::all()->all(),
            fn(User $entity) => $entity->label()
        );

        return [
            new GridColumn(
                'actions',
                'Действия',
                fn(Component $entity) => view('lib.buttons.buttons', [
                    'buttons' => [
                        $this->componentVoter->canEdit($entity) ? [
                            'template' => 'lib.buttons.edit_button',
                            'templateData' => [
                                'url' => route(ComponentEditController::EDIT_ACTION, [
                                    'id' => $entity->id,
                                    'back' => url()->full()
                                ])
                            ]
                        ] : null,
                        $this->componentVoter->canDelete($entity) ? [
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
                false,
                [],
                ['style' => 'min-width: 108px;']
            ),

            new GridColumn(
                'created_at',
                'Дата создания',
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->created_at),
                'created_at',
                new DateFilter('created_at'),
                false,
                true,
                [],
                ['class' => 'text-center align-middle']
            ),
            new GridColumn(
                'updated_at',
                'Дата редактирования',
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->updated_at),
                'updated_at',
                new DateFilter('updated_at'),
                false,
                true,
                [],
                ['class' => 'text-center align-middle']
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
                        fn(PhysicalObject $o) => $o->label()),
                ),
                true,
                true,
                ['style' => 'max-width: 250px;'],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                'metasystem_id',
                'Верхнеуровневая система',
                fn(Component $entity) => $entity->metasystem?->label(),
                null,
                new MultiSelectFilter('metasystem_id',
                    SelectUtils::entityListToLabelMap(
                        Metasystem::all()->all(),
                        fn(Metasystem $o) => $o->label()),
                ),
                false,
                true,
                ['style' => 'max-width: 250px;'],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                'system_id',
                'Система',
                fn(Component $entity) => $entity->system?->label(),
                null,
                new MultiSelectFilter('system_id',
                    SelectUtils::entityListToLabelMap(
                        System::all()->all(),
                        fn(System $o) => $o->label()),
                ),
                false,
                true,
                ['style' => 'max-width: 250px;'],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                'subsystem_id',
                'Подсистема',
                fn(Component $entity) => $entity->subsystem?->label(),
                null,
                new MultiSelectFilter('subsystem_id',
                    SelectUtils::entityListToLabelMap(
                        Subsystem::all()->all(),
                        fn(Subsystem $o) => $o->label()),
                ),
                false,
                true,
                ['style' => 'max-width: 250px;'],
                ['class' => 'align-middle']
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
                ),
                true,
                true,
                ['style' => 'max-width: 250px;'],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                'identifier',
                'Идентификатор компонента',
                fn(Component $entity) => $entity->identifier,
                null,
                new StringFilter('identifier'),
                true,
                true,
                [],
                ['class' => 'align-middle', 'style' => 'min-width: 250px;']
            ),
            new GridColumn(
                'title',
                'Название компонента',
                fn(Component $entity) => $entity->title,
                'title',
                new StringFilter('components.title', 'component_title'),
                true,
                true,
                [],
                ['class' => 'align-middle', 'style' => 'min-width: 250px;']
            ),

            new GridColumn(
                'type',
                'Тип компонента',
                fn(Component $entity) => ComponentType::LABELS[$entity->type] ?? '',
                'type',
                new MultiSelectFilter('type', $this->nullValue(ComponentType::LABELS)),
                false,
                true,
                [],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                'quantity_in_object',
                'Количество в объекте',
                fn(Component $entity) => $entity->quantity_in_object === 0 ? '' : $entity->quantity_in_object,
                'quantity_in_object',
                new IntegerFilter('quantity_in_object'),
                false,
                true,
                ['style' => 'max-width: 100px;'],
                ['class' => 'text-center align-middle']
            ),
            new GridColumn(
                'version',
                'Версия',
                fn(Component $entity) => ComponentVersion::LABELS[$entity->version] ?? '',
                'version',
                new MultiSelectFilter('version', $this->nullValue(ComponentVersion::LABELS)),
                false,
                true,
                [],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                'entry_level',
                'Уровень входимости',
                fn(Component $entity) => $entity->entry_level === 0 ? '' : $entity->entry_level,
                null,
                new IntegerFilter('entry_level'),
                false,
                true,
                ['style' => 'max-width: 100px;'],
                ['class' => 'text-center align-middle']
            ),
            new GridColumn(
                'source_type',
                'Как получаем',
                fn(Component $entity) => ComponentSourceType::LABELS[$entity->source_type] ?? '',
                'source_type',
                new MultiSelectFilter('source_type', $this->nullValue(ComponentSourceType::LABELS)),
                false,
                true,
                [],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                'manufactor_start_way',
                'Способ запуска в производство',
                fn(Component $entity) => ComponentManufactorStartWay::LABELS[$entity->manufactor_start_way] ?? '',
                'manufactor_start_way',
                new MultiSelectFilter('manufactor_start_way', $this->nullValue(ComponentManufactorStartWay::LABELS)),
                false,
                true,
                [],
                ['class' => 'align-middle']
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
                ),
                false,
                true,
                ['style' => 'max-width: 250px;'],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                'constructor_id',
                'Конструктор',
                fn(Component $entity) => $entity->constructor?->label(),
                null,
                new MultiSelectFilter('constructor_id', $userSelectFilterData),
                false,
                true,
                [],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                '3d_status',
                'Статус 3D-модели',
                fn(Component $entity) => Component3dStatus::LABELS[$entity->getAttribute('3d_status')] ?? '',
                '3d_status',
                new MultiSelectFilter('3d_status', $this->nullValue(Component3dStatus::LABELS)),
                true,
                true,
                [],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                '3d_date_plan',
                'Планируемая дата подготовки 3D',
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->getAttribute('3d_date_plan')),
                '3d_date_plan',
                new DateFilter('3d_date_plan'),
                true,
                true,
                [],
                ['class' => 'text-center align-middle']
            ),
            new GridColumn(
                'dd_status',
                'Статус чертежей',
                fn(Component $entity) => ComponentDdStatus::LABELS[$entity->dd_status] ?? '',
                'dd_status',
                new MultiSelectFilter('dd_status', $this->nullValue(ComponentDdStatus::LABELS)),
                true,
                true,
                [],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                'dd_date_plan',
                'Планируемая дата выдачи чертежей',
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->dd_date_plan),
                'dd_date_plan',
                new DateFilter('dd_date_plan'),
                true,
                true,
                [],
                ['class' => 'text-center align-middle']
            ),
            new GridColumn(
                'calc_status',
                'Статус Расчетов',
                fn(Component $entity) => ComponentCalcStatus::LABELS[$entity->calc_status] ?? '',
                'calc_status',
                new MultiSelectFilter('calc_status', $this->nullValue(ComponentCalcStatus::LABELS)),
                true,
                true,
                [],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                'calc_date_plan',
                'Планируемая дата завершения расчетов',
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->calc_date_plan),
                'calc_date_plan',
                new DateFilter('calc_date_plan'),
                true,
                true,
                [],
                ['class' => 'text-center align-middle']
            ),

//
//            new GridColumn(
//                'ttc_number',
//                'ТЗ на расчет (номер)',
//                fn(Component $entity) => $entity->technicalTaskCalculation === null ? '' :
//                    sprintf('<a href="%s" target="_blank">%s</a>',
//                        '/files/' . $entity->technicalTaskCalculation->file_path,
//                        $entity->technicalTaskCalculation->number
//                    ),
//                'technical_task_calculation.number',
//                new StringFilter('technical_task_calculations.number', 'ttc_number'),
//                false,
//                true,
//                [],
//                ['style' => 'min-width: 400px', 'class' => 'align-middle']
//
//            ),
//            new GridColumn(
//                'ttc_title',
//                'ТЗ на расчет (Название)',
//                fn(Component $entity) => $entity->technicalTaskCalculation === null ? '' :
//                    $entity->technicalTaskCalculation->title,
//                'technical_task_calculations.title',
//                new StringFilter('technical_task_calculations.title', 'ttc_title'),
//                false,
//                true,
//                [],
//                ['style' => 'min-width: 400px', 'class' => 'align-middle']
//
//            ),
//            new GridColumn(
//                'ttc_date',
//                'ТЗ на расчет (дата)',
//                fn(Component $entity) => $entity->technicalTaskCalculation === null ? '' :
//                    DateUtils::dateToDisplayFormat($entity->technicalTaskCalculation->date),
//                'technical_task_calculations.date',
//                new DateFilter('technical_task_calculations.date', 'ttc_date'),
//                false,
//                true,
//                [],
//                ['style' => 'min-width: 400px', 'class' => 'align-middle']
//
//            ),

            new GridColumn(
                'constructor_priority',
                'Приоритет конструктора',
                fn(Component $entity) => $entity->constructor_priority,
                null,
                new IntegerFilter('constructor_priority'),
                false,
                true,
                [],
                ['class' => 'text-center align-middle']
            ),
            new GridColumn(
                'constructor_comment',
                'Примечание конструктора',
                fn(Component $entity) => $entity->constructor_comment,
                'constructor_comment',
                new StringFilter('constructor_comment'),
                false,
                true,
                [],
                ['class' => 'align-middle', 'style' => 'min-width: 350px']

            ),
            new GridColumn(
                'manufactor_id',
                'Контроль ЗОК',
                fn(Component $entity) => $entity->manufactor?->label(),
                null,
                new MultiSelectFilter('manufactor_id', $userSelectFilterData),
                false,
                true,
                [],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                'manufactor_status',
                'Статус производства',
                fn(Component $entity) => ComponentManufactorStatus::LABELS[$entity->manufactor_status] ?? '',
                'manufactor_status',
                new MultiSelectFilter('manufactor_status', $this->nullValue(ComponentManufactorStatus::LABELS)),
                false,
                true,
                [],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                'manufactor_date_plan',
                'Планируемая дата производства',
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->getAttribute('manufactor_date_plan')),
                'manufactor_date_plan',
                new DateFilter('manufactor_date_plan'),
                true,
                true,
                [],
                ['class' => 'text-center align-middle']
            ),

            new GridColumn(
                'sz_number',
                'СЗ (номер)',
                fn(Component $entity) => $entity->sz === null || $entity->sz?->file_path === null ? '' :
                    sprintf('<a href="%s" target="_blank">%s</a>',
                        route(SzFileDownloadController::INDEX_ACTION, ['id' => $entity->sz->id]),
                        $entity->sz->number),
                'sz.number',
                new StringFilter('sz.number', 'sz_number'),
                false,
                true,
                [],
                ['style' => 'min-width: 400px', 'class' => 'align-middle']
            ),

            new GridColumn(
                'sz_title',
                'СЗ (Название)',
                fn(Component $entity) => $entity->sz === null ? '' :
                    $entity->sz->title,
                'sz.title',
                new StringFilter('sz.title', 'sz_title'),
                false,
                true,
                [],
                ['style' => 'min-width: 400px', 'class' => 'align-middle']

            ),
            new GridColumn(
                'sz_date',
                'СЗ (дата)',
                fn(Component $entity) => $entity->sz === null ? '' :
                    DateUtils::dateToDisplayFormat($entity->sz->date),
                'sz.date',
                new DateFilter('sz.date', 'sz_date'),
                false,
                true,
                [],
                ['style' => 'min-width: 400px', 'class' => 'align-middle']

            ),
            new GridColumn(
                'manufactor_sz_quantity',
                'Количество в СЗ',
                fn(Component $entity) => $entity->manufactor_sz_quantity === 0 ? '' : $entity->manufactor_sz_quantity,
                null,
                new IntegerFilter('manufactor_sz_quantity'),
                false,
                true,
                ['style' => 'max-width: 120px;'],
                ['class' => 'text-center align-middle',]
            ),
            new GridColumn(
                'manufactor_priority',
                'Приоритет производства',
                fn(Component $entity) => $entity->manufactor_priority === 0 ? '' : $entity->manufactor_priority,
                null,
                new IntegerFilter('manufactor_priority'),
                false,
                true,
                ['style' => 'max-width: 120px;'],
                ['class' => 'text-center align-middle']
            ),
            new GridColumn(
                'manufactor_comment',
                'Примечание ответственного ЗОК',
                fn(Component $entity) => $entity->manufactor_comment,
                'manufactor_comment',
                new StringFilter('manufactor_comment'),
                false,
                true,
                [],
                ['class' => 'align-middle', 'style' => 'min-width: 350px']

            ),
            new GridColumn(
                'purchaser_id',
                'Контроль закупок',
                fn(Component $entity) => $entity->purchaser?->label(),
                null,
                new MultiSelectFilter('purchaser_id', $userSelectFilterData),
                false,
                true,
                [],
                ['class' => 'align-middle']
            ),

            new GridColumn(
                'purchase_status',
                'Статус закупки',
                fn(Component $entity) => ComponentPurchaserStatus::LABELS[$entity->purchase_status] ?? '',
                'purchase_status',
                new MultiSelectFilter('purchase_status', $this->nullValue(ComponentPurchaserStatus::LABELS)),
                true,
                true,
                [],
                ['class' => 'align-middle']
            ),
            new GridColumn(
                'purchase_date_plan',
                'Планируемая дата',
                fn(Component $entity) => DateUtils::dateToDisplayFormat($entity->purchase_date_plan),
                'purchase_date_plan',
                new DateFilter('purchase_date_plan'),
                true,
                true,
                [],
                ['class' => 'text-center align-middle']
            ),

            new GridColumn(
                'purchase_order_number',
                'Заявка (номер)',
                fn(Component $entity) => $entity->purchaseOrder === null ? '' :
                    sprintf('<a href="%s" target="_blank">%s</a>',
                        '/files/' . $entity->purchaseOrder->file_path,
                        $entity->purchaseOrder->number
                    ),
                'purchase_orders.number',
                new StringFilter('purchase_orders.number', 'purchase_order_number'),
                false,
                true,
                [],
                ['style' => 'min-width: 400px', 'class' => 'align-middle']

            ),
            new GridColumn(
                'purchase_order_title',
                'Заявка (Название)',
                fn(Component $entity) => $entity->purchaseOrder === null ? '' :
                    $entity->purchaseOrder->title,
                'purchase_orders.title',
                new StringFilter('purchase_orders.title', 'purchase_order_title'),
                false,
                true,
                [],
                ['style' => 'min-width: 400px', 'class' => 'align-middle']

            ),
            new GridColumn(
                'purchase_order_date',
                'Заявка (дата)',
                fn(Component $entity) => $entity->purchaseOrder === null ? '' :
                    DateUtils::dateToDisplayFormat($entity->purchaseOrder->date),
                'purchase_orders.date',
                new DateFilter('purchase_orders.date', 'purchase_order_date'),
                false,
                true,
                [],
                ['style' => 'min-width: 400px', 'class' => 'align-middle']

            ),

            new GridColumn(
                'purchase_request_quantity',
                'Количество в заявке',
                fn(Component $entity) => $entity->purchase_request_quantity === 0 ? '' : $entity->purchase_request_quantity,
                null,
                new IntegerFilter('purchase_request_quantity'),
                false,
                true,
                ['style' => 'max-width: 120px;'],
                ['class' => 'text-center align-middle',]
            ),
            new GridColumn(
                'purchase_request_priority',
                'Приоритет закупщика',
                fn(Component $entity) => $entity->purchase_request_priority === 0 ? '' : $entity->purchase_request_priority,
                null,
                new IntegerFilter('purchase_request_priority'),
                false,
                true,
                ['style' => 'max-width: 120px;'],
                ['class' => 'text-center align-middle']
            ),
            new GridColumn(
                'purchase_comment',
                'Примечание закупщика',
                fn(Component $entity) => $entity->purchase_comment,
                'purchase_comment',
                new StringFilter('purchase_comment'),
                false,
                true,
                [],
                ['class' => 'align-middle', 'style' => 'min-width: 350px']

            ),
        ];
    }


    public function buildQuery(Request $request): Builder
    {
        $query = Component::query()
            ->leftJoin('users', 'users.id', '=', 'components.constructor_id')
            ->leftJoin('sz', 'sz.id', '=', 'components.sz_id')
            ->leftJoin('purchase_orders', 'purchase_orders.id', '=', 'components.purchase_order_id')
            //->leftJoin('directions', '', '=', 'directions.id');
            ->select('components.*');
        $this->applyFilters($query, $request);
        $this->applySort($query, $request);

        return $query;
    }

    private function nullValue(array $data): array
    {
        $data[0] = 'Не указано';
        return $data;
    }
}
