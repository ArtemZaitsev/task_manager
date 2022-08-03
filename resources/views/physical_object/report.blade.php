@extends('layouts.app')
@section('title') Статистика @endsection
@section('content')
    <style>
        #report-table {
            border: 1px solid;
            border-collapse: collapse;
        }

        #report-table th, td {
            border: 1px solid;
        }

        #report-table .group-header {
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        #report-table .status-header {
            text-align: center;
            vertical-align: middle;
        }

        #report-table td.value,th.value {
            text-align: center;
            vertical-align: middle;
        }


    </style>

    <table class="table" id="report-table">
        <tr>
            <th colspan="3">{{$report['object']->label()}}</th>
            @foreach($report['status'] as $status => $statusData)
                <td class="group-header" colspan="{{ count($statusData) + 1 }}">
                    Всего {{ $report['status_titles'][$status] }}: {{
                    $report['totalStatusWithoutNotRequired'][$status] }}
                </td>
            @endforeach
        </tr>
        <tr>
            <th>Направление</th>
            <th>Компонент</th>
            <th>Всего позиций</th>
            @foreach($report['status'] as $status => $statusData)
                @foreach($statusData as $value => $label)
                    <th class="status-header">
                        {{ $label }}
                    </th>
                @endforeach
            @endforeach
        </tr>
        @foreach($report['rows'] as $row)
            <tr>
                <td>{{ $row['component']->constructor?->direction?->label() }}</td>
                <td>{{ $row['component']->label() }}</td>
                <td class="value">
                    <a target="_blank" href="{{ $filterUrl($row['component']->id) }}">{{
                    $row['total'] }}</a>
                </td>
                @foreach($report['status'] as $field => $statusData)
                    @foreach($statusData as $value => $label)
                        <td class="value">
                            <a target="_blank" href="{{ $filterUrl($row['component']->id, [ $field => [$value]]) }}">
                                {{$row['byStatus'][$field][$value] ?? 0 }}
                            </a>
                        </td>
                    @endforeach
{{--                    <td class="value">--}}
{{--                        <a target="_blank" href="{{ $filterUrl($row['component']->id, [ $field => [0]]) }}">--}}
{{--                            {{$row['byStatus'][$field][0] ?? 0 }}</a>--}}
{{--                    </td>--}}
                @endforeach
            </tr>
        @endforeach
        <tr>
            <th colspan="2">Всего в составе</th>
            <th class="value">{{$report['footer']['total']}}</th>
            @foreach($report['status'] as $field => $statusData)
                @foreach($statusData as $value => $label)
                    <th class="value">{{$report['footer']['status'][$field][$value] ?? 0 }}</th>
                @endforeach
{{--                <th class="value">{{$report['footer']['status'][$field][0] ?? 0 }}</th>--}}
            @endforeach
        </tr>
    </table>
@endsection
