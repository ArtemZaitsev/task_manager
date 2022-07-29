@extends('layouts.app')
@section('title') Список задач @endsection
@section('content')


    <div class="main">

        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success')}}
            </div>
        @endif
    </div>

    <div>
        <a href="{{ route(\App\Http\Controllers\Component\ComponentCreateController::INDEX_ACTION,
 ['back' => url()->full()]) }}">
            Добавить компонент
        </a>
    </div>

    <table class="table table-bordered table-hover">
        <form method="GET" id="filter_form">
            <thead>
            <tr>
                <th></th>
                @foreach($grid['columns'] as $column)
                    <th>
                        @if($column->getOrderField() !== null)
                            <a href="{{  App\Utils\UrlUtils::newSortUrl($column->getOrderField()) }}">{{$column->getLabel()}}</a>
                        @else
                            {{$column->getLabel()}}
                        @endif
                    </th>
                @endforeach
            </tr>
            <tr>
                <td></td>
                @foreach($grid['columns'] as $column)
                    <td>
                        @if($column->getFilter() != null)
                            @include($column->getFilter()->template(), [
                            'filter' => $column->getFilter(),
                            'filterData' => $column->getFilter()->templateData(request())
                            ])
                            <br/>
                            @include('component.filters.filter_buttons', ['filterName' => $column->getFilter()->name()])
                        @endif
                    </td>

                @endforeach
            </tr>
            </thead>
        </form>
        <tbody>
        @foreach($data as $row)
            <tr>
                <td>
                    <a href="{{route(\App\Http\Controllers\Component\ComponentEditController::EDIT_ACTION, ['id' =>
                        $row->id])}}">Edit</a>
                    <a href="{{ route(\App\Http\Controllers\Component\ComponentDeleteController::ROUTE_NAME, ['id' =>
                     $row->id])
                    }}">Delete</a>
                </td>
                @foreach($grid['columns'] as $column)
                    <td>
                        {!! $column->render($row) !!}
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
