@extends('layouts.app')
@section('title') Список компонентов @endsection
@section('content')

    <style>
        .table thead th {
            top: 0;
            z-index: 1;
            position: sticky;
            background-color: #f6eecd;
        }

    </style>



    <div class="main">

        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success')}}
            </div>
        @endif
    </div>

    <div>
        <a class="btn btn-outline-success m-3"
           href="{{ route(\App\Http\Controllers\Component\ComponentCreateController::INDEX_ACTION,
 ['back' => url()->full()]) }}">
            Добавить компонент
        </a>

        <a href="{{ route(\App\Http\Controllers\Component\ComponentController::ROUTE_NAME) }}" class="btn
                        btn-outline-dark m-1">
            Очистить фильтры
        </a>

        @if($taskVoter->canExport())
            <a href="{{ $exportUrl }}" class="btn btn-outline-warning m-1">
                Экспорт в Excel
            </a>
        @endif

        @impersonating()
        <a class="btn btn-outline-info m-1" href="{{ route('impersonate.leave') }}">Выйти из-под
            пользователя</a>
        @endImpersonating


        <div class="position-absolute top-0 end-0">
            <div><b>{{ Illuminate\Support\Facades\Auth::user()->labelFull()}}</b></div>
        </div>


        <script>
            function saveFields() {
                var form = $('#display-fields-form');
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: form.serialize(), // serializes the form's elements.
                    success: function (data) {
                        document.location.reload();
                    },
                    error: function (data) {
                        console.log(data); // show response from the php script.
                    },
                });
            }

            $(function () {
                dialog = $("#display-fields").dialog({
                    autoOpen: false,
                    height: 400,
                    width: 350,
                    modal: true,
                    buttons: {
                        "Сохранить": saveFields,
                        "Отменить": function () {
                            dialog.dialog("close");
                        }
                    },
                    close: function () {

                    }
                });


                $("#show-display-fields").button().on("click", function () {
                    dialog.dialog("open");
                });

                $("#fields_select-all").on("click", function () {
                    $('#display-fields-form input').prop('checked', true);
                })

            });

        </script>


        <button class="btn btn-outline-info m-3" id="show-display-fields">Настроить столбцы</button>
        <div id="display-fields" style="display: none">


            <button id="fields_select-all">Выбрать все</button>
            <form id="display-fields-form"
                  action="{{ route(\App\Http\Controllers\Component\ComponentDisplayFieldsController::ROUTE_NAME) }}">
                @csrf
                <table>
                    @foreach($columns as $column)
                        <tr>
                            <td>{{ $column->getLabel() }}</td>
                            <td>
                                <input type="checkbox" name="fields[{{ $column->getName() }}]"
                                       @if($column->needDisplay()) checked @endif>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </form>
        </div>

        <a href="{{ route(\App\Http\Controllers\Task\TaskController::ACTION_LIST) }}" class="btn
                        btn-outline-success m-1">
            Задачи
        </a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark" style="position:sticky; top: 0; z-index: 1;">
        <form method="GET" id="filter_form">
            <tr class="text-center ">
                <th scope="col" class="text-center">
                    <div class="for-headers">
                        Управление компонентом
                    </div>
                </th>

                @foreach($columns as $column)
                    @if($column->needDisplay())
                        <th scope="col" class="text-center">
                            @if($column->getFilter() != null)
                                <div @if($column->getFilter()->isEnable()) class="filter-applied" @endif>
                                    @include($column->getFilter()->template(), [
                                    'filter' => $column->getFilter(),
                                    'filterData' => $column->getFilter()->templateData(request())
                                    ])
                                    @include('component.filters.filter_buttons', ['filterName' => $column->getFilter()->name()])
                                </div>
                            @endif
                            <div scope="col" class="text-center for-headers">
                                @if($column->getOrderField() !== null)
                                    <a href="{{  App\Utils\UrlUtils::newSortUrl($column->getOrderField()) }}">{{$column->getLabel()}}</a>
                                    @include('component.sort_label', ['field' => $column->getOrderField()])
                                @else
                                    {{$column->getLabel()}}
                                @endif
                            </div>
                        </th>
                    @endif
                @endforeach


            </tr>
        </form>
        </thead>

        <tbody>
        @foreach($data as $row)
            <tr>

                <td style="position: sticky; left: 0; background-color: #f6eecd;">
                    @if($componentVoter->canEditOrDelete($row))
                        <a style="text-decoration: none" href="{{route(\App\Http\Controllers\Component\ComponentEditController::EDIT_ACTION, ['id' =>
                        $row->id, 'back' => url()->full()])}}">
                            <button type="button" class="btn btn-outline-warning" title="Редактировать">
                                <svg width="16" height="16" fill="currentColor"
                                     class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                </svg>
                            </button>
                        </a>
                    @endif
                        @if($componentVoter->canEditOrDelete($row))
                        <a style="text-decoration: none" onclick="return confirm('Точно удалить?')"
                           href="{{ route(\App\Http\Controllers\Component\ComponentDeleteController::ROUTE_NAME,
                                                ['id' => $row->id, 'back' => url()->full()])}}">
                            <button type="button" class="btn btn-outline-danger" title="Удалить">
                                <svg width="16" height="16" fill="currentColor" class="bi bi-x-lg"
                                     viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                          d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                                    <path fill-rule="evenodd"
                                          d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
                                </svg>
                            </button>
                        </a>
                    @endif
                </td>
                @foreach($columns as $column)
                    @if($column->needDisplay())
                        <td data-column-name="{{$column->getName()}}">
                            {!! $column->render($row) !!}
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $data->links() }}

@endsection
