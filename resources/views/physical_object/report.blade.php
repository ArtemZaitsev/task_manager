@extends('layouts.app')
@section('title') Список задач @endsection
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
            text-align: left;
        }

        #report-table .status-header {
            text-align: center;
        }

    </style>

    <table class="table" id="report-table">
        <tr>
            <th colspan="3">{{$report['object']->label()}}</th>
            @foreach($report['status'] as $status => $statusData)
                <td class="group-header" colspan="{{ count($statusData) + 1 }}">
                    Всего {{ $report['status_titles'][$status] }} - {{
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
                <th>Не указано</th>
            @endforeach
        </tr>
        @foreach($report['rows'] as $row)
            <tr>
                <td>{{ $row['component']->constructor?->direction?->label() }}</td>
                <td>{{ $row['component']->label() }}</td>
                <td>
                    <a target="_blank" href="{{ $filterUrl($row['component']->id) }}">{{
                    $row['total'] }}</a>
                </td>
                @foreach($report['status'] as $field => $statusData)
                    @foreach($statusData as $value => $label)
                        <td>
                            <a target="_blank" href="{{ $filterUrl($row['component']->id, [ $field => [$value]]) }}">
                                {{$row['byStatus'][$field][$value] ?? 0 }}
                            </a>
                        </td>
                    @endforeach
                    <td>{{$row['byStatus'][$field][0] ?? 0 }}</td>
                @endforeach
            </tr>
        @endforeach
        <tr>
            <th colspan="2">Всего в составе</th>
            <th>{{$report['footer']['total']}}</th>
            @foreach($report['status'] as $field => $statusData)
                @foreach($statusData as $value => $label)
                    <th>{{$report['footer']['status'][$field][$value] ?? 0 }}</th>
                @endforeach
                <th>{{$report['footer']['status'][$field][0] ?? 0 }}</th>
            @endforeach
        </tr>
    </table>
@endsection
