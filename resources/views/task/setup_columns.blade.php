@extends('layouts.app')
@section('title') Выбор столбцов @endsection
@section('content')
{{--        {{ dd($columns) }};--}}

<div class="container form-check">
        <form method="post" action="{{ route('task.setupColumns.store') }}">
            @csrf
            <table>
                @foreach( $columns as $key => $value)

{{--                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">--}}
{{--                    <label class="form-check-label" for="flexCheckDefault">--}}
{{--                        Default checkbox--}}
{{--                    </label>--}}

                    <tr>
                        <td>
                            <input  class="form-check-input" type="checkbox" id="flexCheckDefault" name="{{ $key }}" id="{{ $key }}"
                                   @if(\App\Utils\ColumnUtils::isColumnEnabled($key)) checked  @endif>
                        </td>
                        <td>
                            <label class="form-check-label" for="{{ $key }}">{{ $value }}</label>
                        </td>
                    </tr>
                @endforeach
            </table>
            <button type="submit" class="btn btn-danger m-3">Сохранить</button>
        </form>
    </div>
@endsection
