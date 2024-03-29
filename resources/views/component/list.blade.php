@extends('layouts.grid')
@section('title') Список компонентов @endsection
@section('grid')

    <style>

        .table thead th {
            background-color: #f6eecd;
            top:0;
            z-index: 1;
            position: sticky
        }


        .table thead th:first-child {
            left:0;
            z-index: 2;
            position: sticky;
            background-color: #f6eecd;
        }

        .table tbody tr td:first-child {
            left:0;
            z-index: 0;
            position: sticky;
            background-color: #f6eecd;
        }
    </style>




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

        @if($voter->canExport())
            <a href="{{ $exportUrl }}" class="btn btn-outline-warning m-1">
                Экспорт в Excel
            </a>
        @endif


       @include('lib.columns_form', ['url' => route
       (App\Http\Controllers\Component\ComponentController::SAVE_FIELDS_ROUTE_NAME),
       'fields' => $grid->getFields()])

    <table class="table table-bordered table-hover">
        <thead class="thead-dark" style="position:sticky; top: 0; z-index: 1;">
        <form method="GET" id="filter_form">
            <tr class="text-center ">
                @foreach($grid->getColumns() as $column)
                    @if($grid->needDisplay($column->getName()))
                        <th scope="col" class="text-center"
                        @foreach($column->getHeaderAttrs() as $attr => $value) {{ $attr}}="{{$value}}" @endforeach>
                            @if($column->getFilter() != null)
                                <div @if($column->getFilter()->isEnable()) class="filter-applied" @endif>
                                    @include($column->getFilter()->template(), [
                                    'filter' => $column->getFilter(),
                                    'filterData' => $column->getFilter()->templateData(request())
                                    ])
                                    @include('lib.filters.filter_buttons', ['filterName' => $column->getFilter()->name()])
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
                @foreach($grid->getColumns() as $column)
                    @if($grid->needDisplay($column->getName()))
                        <td data-column-name="{{$column->getName()}}" @foreach($column->getCellAttrs() as $attr => $value) {{ $attr}}="{{$value}}" @endforeach>
                            {!! $column->render($row) !!}
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $data->links() }} <b>Всего:</b> {{ $data->total() }}

@endsection
