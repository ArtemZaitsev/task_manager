@extends('layouts.app')
@section('title') {{ $title }} @endsection
@section('content')

    <style>
        div.form-group {
            margin-bottom: 20px !important;
        }
    </style>

    <div class="container">
        <h3>{{ $title  }}</h3>
        <form method="post" enctype="multipart/form-data">
            @csrf


            @include('lib.fields.input', [
                       'required' => true,
                       'label' => 'Номер',
                       'fieldName' => 'number',
                       'currentValue' => $entity->number
               ])

            @include('lib.fields.input', [
                  'required' => false,
                  'label' => 'Название',
                  'fieldName' => 'title',
                  'currentValue' => $entity->title
          ])

            @include('lib.fields.date', [
                       'required' => true,
                       'label' => 'Дата',
                       'fieldName' => 'date',
                       'currentValue' => $entity->date,
               ])

            @include('lib.fields.select', [
                       'required' => false,
                       'label' => 'Инициатор',
                       'fieldName' => 'initiator_id',
                       'currentValue' =>$entity->initiator_id,
                       'multiple' => false,
                       'data' => $userSelectData
               ])

            @include('lib.fields.select', [
                      'required' => false,
                      'label' => 'Адресат',
                      'fieldName' => 'target_user_id',
                      'currentValue' =>$entity->target_user_id,
                      'multiple' => false,
                      'data' => $userSelectData
              ])



            @include('lib.fields.file', [
                       'required' => false,
                       'label' => 'Файл',
                       'fieldName' => 'file',
               ])

            <button type="submit" class="btn btn-info mt-3">Сохранить</button>
        </form>
    </div>
@endsection
