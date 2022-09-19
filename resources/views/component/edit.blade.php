@extends('layouts.app')
@section('title') {{ $title }} @endsection
@section('content')

    <style>
        div.form-group {
            margin-bottom: 20px !important;
        }
    </style>

    <form method="post">
        <div class="container">
            @csrf

            @if($fieldsToEdit === null || in_array('physical_object_id', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Объект',
                        'fieldName' => 'physical_object_id',
                        'currentValue' => $entity->physical_object_id,
                        'multiple' => false,
                        'data' => $physicalObjectsSelectData
                 ])
            @endif

            @if($fieldsToEdit === null || in_array('metasystem_id', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Верхнеуровневая система',
                        'fieldName' => 'metasystem_id',
                        'currentValue' => $entity->metasystem_id,
                        'multiple' => false,
                        'data' => $metasystemsSelectData
                 ])
            @endif

            @if($fieldsToEdit === null || in_array('system_id', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Система',
                        'fieldName' => 'system_id',
                        'currentValue' => $entity->system_id,
                        'multiple' => false,
                        'data' => $systemsSelectData
                 ])
            @endif

            @if($fieldsToEdit === null || in_array('subsystem_id', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Подсистема',
                        'fieldName' => 'subsystem_id',
                        'currentValue' => $entity->subsystem_id,
                        'multiple' => false,
                        'data' => $subsystemsSelectData
                 ])
            @endif



            @if($fieldsToEdit === null || in_array('is_highlevel', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => true,
                        'label' => 'Верхнеуровневый компонент',
                        'fieldName' => 'is_highlevel',
                        'currentValue' => $entity->is_highlevel,
                        'multiple' => false,
                        'data' => [1=> 'Да', 0=>'Нет']
                 ])
            @endif

            @if($fieldsToEdit === null || in_array('relative_component_id', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Родительский компонент',
                        'fieldName' => 'relative_component_id',
                        'currentValue' => $entity->relative_component_id,
                        'multiple' => false,
                        'data' => $componentsSelectData
                        ])
            @endif


            @if($fieldsToEdit === null || in_array('title', $fieldsToEdit))
                @include('lib.fields.input', [
                        'required' => true,
                        'label' => 'Название',
                        'fieldName' => 'title',
                        'currentValue' => $entity->title
                        ])
            @endif

            @if($fieldsToEdit === null || in_array('identifier', $fieldsToEdit))
                @include('lib.fields.input', [
                        'required' => false,
                        'label' => 'Идентификатор',
                        'fieldName' => 'identifier',
                        'currentValue' => $entity->identifier
                ])
            @endif

            @if($fieldsToEdit === null || in_array('entry_level', $fieldsToEdit))
                @include('lib.fields.number', [
                        'required' => false,
                        'label' => 'Уровень входимости',
                        'fieldName' => 'entry_level',
                        'currentValue' => $entity->entry_level
                ])
            @endif

            @if($fieldsToEdit === null || in_array('source_type', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Как получаем',
                        'fieldName' => 'source_type',
                        'currentValue' => $entity->source_type,
                        'multiple' => false,
                        'data' =>  App\Models\Component\ComponentSourceType::LABELS
                ])
            @endif

            @if($fieldsToEdit === null || in_array('version', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Версия',
                        'fieldName' => 'version',
                        'currentValue' => $entity->version,
                        'multiple' => false,
                        'data' =>  App\Models\Component\ComponentVersion::LABELS
                ])
            @endif

            @if($fieldsToEdit === null || in_array('type', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Тип',
                        'fieldName' => 'type',
                        'currentValue' => $entity->type,
                        'multiple' => false,
                        'data' =>  App\Models\Component\ComponentType::LABELS
                ])
            @endif

            @if($fieldsToEdit === null || in_array('quantity_in_object', $fieldsToEdit))
                @include('lib.fields.number', [
                        'required' => false,
                        'label' => 'Количество в объекте',
                        'fieldName' => 'quantity_in_object',
                        'currentValue' => $entity->quantity_in_object,
                ])
            @endif

            @if($fieldsToEdit === null || in_array('manufactor_start_way', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Способ запуска в производство',
                        'fieldName' => 'manufactor_start_way',
                        'currentValue' =>$entity->manufactor_start_way,
                        'multiple' => false,
                        'data' => \App\Models\Component\ComponentManufactorStartWay::LABELS
                ])
            @endif

            @if($fieldsToEdit === null || in_array('constructor_id', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Конструктор',
                        'fieldName' => 'constructor_id',
                        'currentValue' =>$entity->constructor_id,
                        'multiple' => false,
                        'data' => $userSelectData
                ])
            @endif

            @if($fieldsToEdit === null || in_array('3d_status', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Статус 3D',
                        'fieldName' => '3d_status',
                        'currentValue' =>$entity->getAttribute('3d_status'),
                        'multiple' => false,
                        'data' => App\Models\Component\Component3dStatus::LABELS
                ])
            @endif

            @if($fieldsToEdit === null || in_array('3d_date_plan', $fieldsToEdit))
                @include('lib.fields.date', [
                        'required' => false,
                        'label' => 'Планируемая дата подготовки 3D',
                        'fieldName' => '3d_date_plan',
                        'currentValue' => $entity->getAttribute('3d_date_plan'),
                ])
            @endif

            @if($fieldsToEdit === null || in_array('dd_status', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Статус чертежей',
                        'fieldName' => 'dd_status',
                        'currentValue' =>$entity->getAttribute('dd_status'),
                        'multiple' => false,
                        'data' => App\Models\Component\ComponentDdStatus::LABELS
                ])
            @endif

            @if($fieldsToEdit === null || in_array('dd_date_plan', $fieldsToEdit))
                @include('lib.fields.date', [
                        'required' => false,
                        'label' => 'Планируемая дата подготовки чертежей',
                        'fieldName' => 'dd_date_plan',
                        'currentValue' => $entity->getAttribute('dd_date_plan'),
                ])
            @endif

{{--            @if($fieldsToEdit === null || in_array('drawing_files', $fieldsToEdit))--}}
{{--                @include('lib.fields.input', [--}}
{{--                        'required' => false,--}}
{{--                        'label' => 'Чертежи',--}}
{{--                        'fieldName' => 'drawing_files',--}}
{{--                        'currentValue' => $entity->getAttribute('drawing_files'),--}}
{{--                ])--}}
{{--            @endif--}}

{{--            @if($fieldsToEdit === null || in_array('drawing_date', $fieldsToEdit))--}}
{{--                @include('lib.fields.date', [--}}
{{--                        'required' => false,--}}
{{--                        'label' => 'Дата чертежей',--}}
{{--                        'fieldName' => 'drawing_date',--}}
{{--                        'currentValue' => $entity->getAttribute('drawing_date'),--}}
{{--                ])--}}
{{--            @endif--}}




            @if($fieldsToEdit === null || in_array('calc_status', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Статус расчетов',
                        'fieldName' => 'calc_status',
                        'currentValue' =>$entity->calc_status,
                        'multiple' => false,
                        'data' => App\Models\Component\ComponentCalcStatus::LABELS
                ])
            @endif

            @if($fieldsToEdit === null || in_array('calc_date_plan', $fieldsToEdit))
                @include('lib.fields.date', [
                        'required' => false,
                        'label' => 'Планируемая дата завершения расчетов',
                        'fieldName' => 'calc_date_plan',
                        'currentValue' => $entity->calc_date_plan,
                ])
            @endif

            @if($fieldsToEdit === null || in_array('technical_task_calculation_id', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'ТЗ',
                        'fieldName' => 'technical_task_calculation_id',
                        'currentValue' => $entity->technical_task_calculation_id,
                        'multiple' => false,
                        'data' => $technicalTaskCalculationSelectData
                        ])
            @endif


            @if($fieldsToEdit === null || in_array('tz_files', $fieldsToEdit))
                @include('lib.fields.input', [
                        'required' => false,
                        'label' => 'ТЗ',
                        'fieldName' => 'tz_files',
                        'currentValue' => $entity->tz_files,
                ])
            @endif

            @if($fieldsToEdit === null || in_array('tz_date', $fieldsToEdit))
                @include('lib.fields.date', [
                        'required' => false,
                        'label' => 'Дата ТЗ',
                        'fieldName' => 'tz_date',
                        'currentValue' => $entity->tz_date,
                ])
            @endif

            @if($fieldsToEdit === null || in_array('constructor_priority', $fieldsToEdit))
                @include('lib.fields.number', [
                        'required' => false,
                        'label' => 'Приоритет конструктора',
                        'fieldName' => 'constructor_priority',
                        'currentValue' => $entity->constructor_priority,
                ])
            @endif


            @if($fieldsToEdit === null || in_array('constructor_comment', $fieldsToEdit))
                @include('lib.fields.input', [
                        'required' => false,
                        'label' => 'Комментарии конструктора',
                        'fieldName' => 'constructor_comment',
                        'currentValue' => $entity->constructor_comment,
                ])
            @endif

            @if($fieldsToEdit === null || in_array('manufactor_id', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Контроль ЗОК',
                        'fieldName' => 'manufactor_id',
                        'currentValue' => $entity->manufactor_id,
                        'multiple' => false,
                        'data' => $userSelectData
                        ])
            @endif

            @if($fieldsToEdit === null || in_array('manufactor_status', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Статус производства',
                        'fieldName' => 'manufactor_status',
                        'currentValue' => $entity->manufactor_status,
                        'multiple' => false,
                        'data' => \App\Models\Component\ComponentManufactorStatus::LABELS,
                        ])
            @endif
            @if($fieldsToEdit === null || in_array('manufactor_date_plan', $fieldsToEdit))
                @include('lib.fields.date', [
                        'required' => false,
                        'label' => 'Планируемая дата производства',
                        'fieldName' => 'manufactor_date_plan',
                        'currentValue' => $entity->manufactor_date_plan,
                ])
            @endif

            @if($fieldsToEdit === null || in_array('sz_id', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Служебная записка',
                        'fieldName' => 'sz_id',
                        'currentValue' => $entity->sz_id,
                        'multiple' => false,
                        'data' => $szSelectData
                        ])
            @endif

            @if($fieldsToEdit === null || in_array('manufactor_sz_quantity', $fieldsToEdit))
                @include('lib.fields.number', [
                        'required' => false,
                        'label' => 'Количество в СЗ',
                        'fieldName' => 'manufactor_sz_quantity',
                        'currentValue' => $entity->manufactor_sz_quantity,
                ])
            @endif

            @if($fieldsToEdit === null || in_array('manufactor_priority', $fieldsToEdit))
                @include('lib.fields.number', [
                        'required' => false,
                        'label' => 'Приоритет производства',
                        'fieldName' => 'manufactor_priority',
                        'currentValue' => $entity->manufactor_priority,
                ])
            @endif

            @if($fieldsToEdit === null || in_array('manufactor_comment', $fieldsToEdit))
                @include('lib.fields.input', [
                        'required' => false,
                        'label' => 'Комментарий производства',
                        'fieldName' => 'manufactor_comment',
                        'currentValue' => $entity->manufactor_comment,
                ])
            @endif
            @if($fieldsToEdit === null || in_array('purchaser_id', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Контроль закупок',
                        'fieldName' => 'purchaser_id',
                        'currentValue' => $entity->purchaser_id,
                        'multiple' => false,
                        'data' => $userSelectData
                        ])
            @endif

            @if($fieldsToEdit === null || in_array('purchase_status', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Статус закупки',
                        'fieldName' => 'purchase_status',
                        'currentValue' => $entity->purchase_status,
                        'multiple' => false,
                        'data' => \App\Models\Component\ComponentPurchaserStatus::LABELS,
                        ])
            @endif


            @if($fieldsToEdit === null || in_array('purchase_date_plan', $fieldsToEdit))
                @include('lib.fields.date', [
                        'required' => false,
                        'label' => 'Планируемая дата закупки',
                        'fieldName' => 'purchase_date_plan',
                        'currentValue' => $entity->purchase_date_plan,
                ])
            @endif

            @if($fieldsToEdit === null || in_array('purchase_order_id', $fieldsToEdit))
                @include('lib.fields.select', [
                        'required' => false,
                        'label' => 'Заявка на закупку',
                        'fieldName' => 'purchase_order_id',
                        'currentValue' => $entity->purchase_order_id,
                        'multiple' => false,
                        'data' => $purchaseOrderSelectData
                        ])
            @endif


            @if($fieldsToEdit === null || in_array('purchase_request_quantity', $fieldsToEdit))
                @include('lib.fields.number', [
                        'required' => false,
                        'label' => 'Количество в заявке',
                        'fieldName' => 'purchase_request_quantity',
                        'currentValue' => $entity->purchase_request_quantity,
                ])
            @endif

            @if($fieldsToEdit === null || in_array('purchase_request_priority', $fieldsToEdit))
                @include('lib.fields.number', [
                        'required' => false,
                        'label' => 'Приоритет закупок',
                        'fieldName' => 'purchase_request_priority',
                        'currentValue' => $entity->purchase_request_priority,
                ])
            @endif

            @if($fieldsToEdit === null || in_array('purchase_comment', $fieldsToEdit))
                @include('lib.fields.input', [
                        'required' => false,
                        'label' => 'Комментарий закупок',
                        'fieldName' => 'purchase_comment',
                        'currentValue' => $entity->purchase_comment,
                ])
            @endif


            <button type="submit" class="btn btn-info mt-3">Сохранить</button>
        </div>
    </form>

@endsection
