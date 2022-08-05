@extends('layouts.app')
@section('title') {{$report['object']->label()}} @endsection
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

        #report-table td.value, th.value {
            text-align: center;
            vertical-align: middle;
        }

        #report-table .status-3d_status {
            background-color: #fdf5dc;
        }

        #report-table .status-dd_status {
            background-color: #e9ffd8;
        }

        #report-table .status-calc_status {
            background-color: #dce5fd;
        }

        #report-table .status-manufactor_status {
            background-color: #fadcfd;
        }

        #report-table .status-purchase_status {
            background-color: #dcfdf4;
        }


        #report-table tbody th {
            position: static;
        }
    </style>

    <table class="table" id="report-table">
        <tr>
            <th colspan="3" class="fixed-left">{{$report['object']->label()}}</th>
            @foreach($report['status'] as $status => $statusData)
                <td class="group-header status-{{$status}}" colspan="{{ count($statusData) + 1 }}">
                    Всего {{ $report['status_titles'][$status] }}:
                    {{ $report['totalStatusWithoutNotRequired'][$status] ?? '' }}
                </td>
            @endforeach
        </tr>
        <tr>

            <th class="fixed-left">Направление</th>
            <th class="fixed-left component">Компонент</th>
            <th class="fixed-left">Всего позиций</th>
            @foreach($report['status'] as $status => $statusData)
                @foreach($statusData as $value => $label)
                    <th class="status-header status-{{$status}}">
                        {{ $label }}
                    </th>
                @endforeach
                <th class="not-specified status-{{$status}}">Не указано</th>
            @endforeach
        </tr>
        @foreach($report['rows'] as $row)
            <tr>
                <td class="fixed-left">{{ $row['component']->constructor?->direction?->label() }}</td>
                <td class="fixed-left component">{{ $row['component']->label() }}</td>
                <td class="value fixed-left">
                    <a target="_blank" href="{{ $filterUrl($row['component']->id) }}">{{
                    $row['total'] }}</a>
                </td>
                @foreach($report['status'] as $field => $statusData)
                    @foreach($statusData as $value => $label)
                        <td class="value status-{{$field}}">
                            <a target="_blank" href="{{ $filterUrl($row['component']->id, [ $field => [$value]]) }}">
                                {{$row['byStatus'][$field][$value] ?? 0 }}
                            </a>
                        </td>
                    @endforeach
                    <td class="value status-{{$field}}">
                        <a target="_blank" href="{{ $filterUrl($row['component']->id, [ $field => [0]]) }}">
                            {{$row['byStatus'][$field][0] ?? 0 }}</a>
                    </td>
                @endforeach
            </tr>
        @endforeach
        <tr>
            <th class="fixed-left" colspan="2">Всего в составе</th>
            <th class="value fixed-left">{{$report['footer']['total']}}</th>
            @foreach($report['status'] as $field => $statusData)
                @foreach($statusData as $value => $label)
                    <th class="value status-{{$field}}">{{$report['footer']['status'][$field][$value] ?? 0 }}</th>
                @endforeach
                <th class="value status-{{$field}}">{{$report['footer']['status'][$field][0] ?? 0 }}</th>
            @endforeach
        </tr>
    </table>
@endsection
