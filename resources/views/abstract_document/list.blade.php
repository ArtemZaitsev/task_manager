@extends('layouts.grid')
@section('title'){{ $title }} @endsection
@section('grid')

    <style>
        .table thead th {
            top: 0;
            z-index: 1;
            position: sticky;
            background-color: #f6eecd;
        }

    </style>


<h2>{{ $title }}</h2>
    <div>
        <a class="btn btn-outline-success m-3"
           href="{{ $links['create'] }}">Добавить</a>

        <a href="{{ $links['reset'] }}" class="btn btn-outline-dark m-1">
            Очистить фильтры
        </a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark" style="position:sticky; top: 0; z-index: 1;">
        <form method="GET" id="filter_form">
            <tr class="text-center ">

                @foreach($grid->getColumns() as $column)
                    @if($grid->needDisplay($column->getName()))
                        <th scope="col" class="text-center">
                            @if($column->getFilter() != null)
                                <div @if($column->getFilter()->isEnable()) class="filter-applied" @endif>
                                    @include($column->getFilter()->template(), [
                                    'filter' => $column->getFilter(),
                                    'filterData' => $column->getFilter()->templateData(request())
                                    ])
                                    @include('lib.filters.filter_buttons', ['filterName' => $column->getFilter()->name
                                    ()])
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
