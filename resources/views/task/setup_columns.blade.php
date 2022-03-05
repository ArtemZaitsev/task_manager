@extends('layouts.app')
@section('title') Выбор столбцов @endsection
@section('content')
{{--        {{ dd($columns) }};--}}
    <div class="container">
        <form method="post" action="{{ route('task.setupColumns.store') }}">
            @csrf
            <table>
                @foreach( $columns as $key => $value)

                    <tr>
                        <td>
                            <input type="checkbox" name="{{ $key }}" id="{{ $key }}"
                                   @if(\App\Utils\ColumnUtils::isColumnEnabled($key)) checked  @endif>
                        </td>
                        <td>
                            <label for="{{ $key }}">{{ $value }}</label>
                        </td>
                    </tr>
                @endforeach
            </table>
            <button type="submit" class="btn btn-danger m-3">Сохранить</button>
        </form>
    </div>
@endsection
