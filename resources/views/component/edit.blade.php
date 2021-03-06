@extends('layouts.app')
@section('title') {{ $title }} @endsection
@section('content')

    <form method="post">
        @csrf

        @if($fieldsToEdit === null || in_array('physical_object_id', $fieldsToEdit))
            @include('component.fields.select', [
                    'required' => false,
                    'label' => 'Объект',
                    'fieldName' => 'physical_object_id',
                    'currentValue' => $entity->physicalObjectId,
                    'multiple' => false,
                    'data' => $physicalObjectsSelectData
             ])
        @endif

        @if($fieldsToEdit === null || in_array('relative_component_id', $fieldsToEdit))
            @include('component.fields.select', [
                    'required' => false,
                    'label' => 'Родительский компонент',
                    'fieldName' => 'relative_component_id',
                    'currentValue' => $entity->relative_component_id,
                    'multiple' => false,
                    'data' => $componentsSelectData
                    ])
        @endif


        @if($fieldsToEdit === null || in_array('title', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => true,
                    'label' => 'Название',
                    'fieldName' => 'title',
                    'currentValue' => $entity->title
                    ])
        @endif

        @if($fieldsToEdit === null || in_array('identifier', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'Идентификатор',
                    'fieldName' => 'identifier',
                    'currentValue' => $entity->identifier
            ])
        @endif

        @if($fieldsToEdit === null || in_array('entry_level', $fieldsToEdit))
            @include('component.fields.number', [
                    'required' => false,
                    'label' => 'Уровень входимости',
                    'fieldName' => 'entry_level',
                    'currentValue' => $entity->entry_level
            ])
        @endif

        @if($fieldsToEdit === null || in_array('source_type', $fieldsToEdit))
            @include('component.fields.select', [
                    'required' => false,
                    'label' => 'Как получаем',
                    'fieldName' => 'source_type',
                    'currentValue' => $entity->source_type,
                    'multiple' => false,
                    'data' =>  App\Models\Component\ComponentSourceType::LABELS
            ])
        @endif

        @if($fieldsToEdit === null || in_array('version', $fieldsToEdit))
            @include('component.fields.select', [
                    'required' => false,
                    'label' => 'Версия',
                    'fieldName' => 'version',
                    'currentValue' => $entity->version,
                    'multiple' => false,
                    'data' =>  App\Models\Component\ComponentVersion::LABELS
            ])
        @endif

        @if($fieldsToEdit === null || in_array('source_type', $fieldsToEdit))
            @include('component.fields.select', [
                    'required' => false,
                    'label' => 'Тип',
                    'fieldName' => 'source_type',
                    'currentValue' => $entity->source_type,
                    'multiple' => false,
                    'data' =>  App\Models\Component\ComponentSourceType::LABELS
            ])
        @endif

        @if($fieldsToEdit === null || in_array('constructor_id', $fieldsToEdit))
            @include('component.fields.select', [
                    'required' => false,
                    'label' => 'Конструктор',
                    'fieldName' => 'constructor_id',
                    'currentValue' =>$entity->constructor_id,
                    'multiple' => false,
                    'data' => $userSelectData
            ])
        @endif

        @if($fieldsToEdit === null || in_array('3d_status', $fieldsToEdit))
            @include('component.fields.select', [
                    'required' => false,
                    'label' => 'Статус 3D',
                    'fieldName' => '3d_status',
                    'currentValue' =>$entity->getAttribute('3d_status'),
                    'multiple' => false,
                    'data' => App\Models\Component\Component3dStatus::LABELS
            ])
        @endif

        @if($fieldsToEdit === null || in_array('3d_date_plan', $fieldsToEdit))
            @include('component.fields.date', [
                    'required' => false,
                    'label' => 'Планируемая дата подготовки 3D',
                    'fieldName' => '3d_date_plan',
                    'currentValue' => $entity->getAttribute('3d_date_plan'),
            ])
        @endif

        @if($fieldsToEdit === null || in_array('dd_status', $fieldsToEdit))
            @include('component.fields.select', [
                    'required' => false,
                    'label' => 'Статус КД',
                    'fieldName' => 'dd_status',
                    'currentValue' =>$entity->getAttribute('dd_status'),
                    'multiple' => false,
                    'data' => App\Models\Component\ComponentDdStatus::LABELS
            ])
        @endif

        @if($fieldsToEdit === null || in_array('dd_date_plan', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'Планируемая дата подготовки КД',
                    'fieldName' => 'dd_date_plan',
                    'currentValue' => $entity->getAttribute('dd_date_plan'),
            ])
        @endif

        @if($fieldsToEdit === null || in_array('drawing_files', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'Чертежи',
                    'fieldName' => 'drawing_files',
                    'currentValue' => $entity->getAttribute('drawing_files'),
            ])
        @endif

        @if($fieldsToEdit === null || in_array('drawing_date_plan', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'Дата чертежей',
                    'fieldName' => 'drawing_date_plan',
                    'currentValue' => $entity->getAttribute('drawing_date_plan'),
            ])
        @endif




        @if($fieldsToEdit === null || in_array('calc_status', $fieldsToEdit))
            @include('component.fields.select', [
                    'required' => false,
                    'label' => 'Статус расчетов',
                    'fieldName' => 'calc_status',
                    'currentValue' =>$entity->calc_status,
                    'multiple' => false,
                    'data' => App\Models\Component\ComponentCalcStatus::LABELS
            ])
        @endif

        @if($fieldsToEdit === null || in_array('calc_date_plan', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'Планируемая дата завершения расчетов',
                    'fieldName' => 'calc_date_plan',
                    'currentValue' => $entity->calc_date_plan,
            ])
        @endif

        @if($fieldsToEdit === null || in_array('tz_files', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'ТЗ',
                    'fieldName' => 'tz_files',
                    'currentValue' => $entity->tz_files,
            ])
        @endif

        @if($fieldsToEdit === null || in_array('tz_date_plane', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'Дата ТЗ',
                    'fieldName' => 'tz_date_plane',
                    'currentValue' => $entity->tz_date_plane,
            ])
        @endif

        @if($fieldsToEdit === null || in_array('constructor_priority', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'Приоритет конструктора',
                    'fieldName' => 'constructor_priority',
                    'currentValue' => $entity->constructor_priority,
            ])
        @endif

        @if($fieldsToEdit === null || in_array('constructor_comment', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'Комментарии конструктора',
                    'fieldName' => 'constructor_comment',
                    'currentValue' => $entity->constructor_comment,
            ])
        @endif

        @if($fieldsToEdit === null || in_array('manufactor_id', $fieldsToEdit))
            @include('component.fields.select', [
                    'required' => false,
                    'label' => 'Ответственный ЗОК',
                    'fieldName' => 'manufactor_id',
                    'currentValue' => $entity->manufactor_id,
                    'multiple' => false,
                    'data' => $userSelectData
                    ])
        @endif

        @if($fieldsToEdit === null || in_array('manufactor_status', $fieldsToEdit))
            @include('component.fields.select', [
                    'required' => false,
                    'label' => 'Статус производства',
                    'fieldName' => 'manufactor_status',
                    'currentValue' => $entity->manufactor_status,
                    'multiple' => false,
                    'data' => \App\Models\Component\ComponentManufactorStatus::LABELS,
                    ])
        @endif


        @if($fieldsToEdit === null || in_array('manufactor_sz_files', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'СЗ',
                    'fieldName' => 'manufactor_sz_files',
                    'currentValue' => $entity->manufactor_sz_files,
            ])
        @endif

        @if($fieldsToEdit === null || in_array('manufactor_sz_date', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'Дата СЗ',
                    'fieldName' => 'manufactor_sz_date',
                    'currentValue' => $entity->manufactor_sz_date,
            ])
        @endif

        @if($fieldsToEdit === null || in_array('manufactor_sz_quantity', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'Количество в СЗ',
                    'fieldName' => 'manufactor_sz_quantity',
                    'currentValue' => $entity->manufactor_sz_quantity,
            ])
        @endif

        @if($fieldsToEdit === null || in_array('manufactor_priority', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'Приоритет производства',
                    'fieldName' => 'manufactor_priority',
                    'currentValue' => $entity->manufactor_priority,
            ])
        @endif

        @if($fieldsToEdit === null || in_array('manufactor_comment', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'Комментарий производства',
                    'fieldName' => 'manufactor_comment',
                    'currentValue' => $entity->manufactor_comment,
            ])
        @endif
        @if($fieldsToEdit === null || in_array('purchaser_id', $fieldsToEdit))
            @include('component.fields.select', [
                    'required' => false,
                    'label' => 'Ответственный закупщик',
                    'fieldName' => 'purchaser_id',
                    'currentValue' => $entity->purchaser_id,
                    'multiple' => false,
                    'data' => $userSelectData
                    ])
        @endif

        @if($fieldsToEdit === null || in_array('purchase_status', $fieldsToEdit))
            @include('component.fields.select', [
                    'required' => false,
                    'label' => 'Статус закупки',
                    'fieldName' => 'purchase_status',
                    'currentValue' => $entity->purchase_status,
                    'multiple' => false,
                    'data' => \App\Models\Component\ComponentPurchaserStatus::LABELS,
                    ])
        @endif


        @if($fieldsToEdit === null || in_array('purchase_date', $fieldsToEdit))
            @include('component.fields.input', [
                    'required' => false,
                    'label' => 'Планируемая дата поставки',
                    'fieldName' => 'purchase_date',
                    'currentValue' => $entity->manufactor_sz_files,
            ])
        @endif



        <button type="submit" class="btn btn-info mt-3">Сохранить</button>
    </form>

@endsection
