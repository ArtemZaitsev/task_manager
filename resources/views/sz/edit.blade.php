@extends('layouts.app')
@section('title') Создание СЗ @endsection
@section('content')

    <style>
        div.form-group {
            margin-bottom: 20px !important;
        }
    </style>

    <form method="post" enctype="multipart/form-data">
        @csrf

        <div class="container">
            @include('lib.fields.input', [
                       'required' => true,
                       'label' => 'Номер',
                       'fieldName' => 'number',
                       'currentValue' => $entity->number
               ])

            @include('lib.fields.date', [
                       'required' => true,
                       'label' => 'Дата',
                       'fieldName' => 'date',
                       'currentValue' => $entity->date,
               ])

            @include('lib.fields.file', [
                       'required' => true,
                       'label' => 'Файл',
                       'fieldName' => 'szFile',
               ])

            <button type="submit" class="btn btn-info mt-3">Сохранить</button>

        </div>
    </form>
@endsection
