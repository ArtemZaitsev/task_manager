@extends('layouts.app')
@section('title') {{ $title }} @endsection
@section('content')

    <form method="post">
        @csrf
        @if($fieldsToEdit === null || in_array('title', $fieldsToEdit))
            @include('component.fields.input', ['required' => true, 'label' => 'Название', 'fieldName' => 'title',
            'currentValue' =>
            $entity->title])
        @endif

        @if($fieldsToEdit === null || in_array('identifier', $fieldsToEdit))
            @include('component.fields.input', ['required' => false, 'label' => 'Идентификатор', 'fieldName' =>
            'identifier',
            'currentValue' =>
            $entity->identifier])
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

        @if($fieldsToEdit === null || in_array('source_type', $fieldsToEdit))
            @include('component.fields.select', ['required' => false, 'label' => 'Тип', 'fieldName' => 'source_type',
            'currentValue' =>        $entity->source_type, 'multiple' => false, 'data' =>  App\Models\Component\ComponentSourceType::LABELS])
        @endif

        @if($fieldsToEdit === null || in_array('physical_objects', $fieldsToEdit))
            @include('component.fields.select', [ 'required' => false,'label' => 'Обьекты', 'fieldName' => 'physical_objects',
            'currentValue' => $entity->physicalObjects()->allRelatedIds()->toArray(), 'multiple' => true, 'data' =>
            $physicalObjectsSelectData])
        @endif

        @if($fieldsToEdit === null || in_array('relative_component_id', $fieldsToEdit))
            @include('component.fields.select', [ 'required' => false, 'label' => 'Род компонент', 'fieldName' =>
            'relative_component_id',
            'currentValue' => $entity->relative_component_id, 'multiple' => false,
            'data' => $componentsSelectData])
        @endif

        <button type="submit" class="btn btn-info mt-3">Сохранить</button>
    </form>

@endsection
