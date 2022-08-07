@extends('layouts.app')
@section('title') {{$report['object']->label()}} @endsection
@section('content')
    <style>
        #report-table a.data-link {
            font-size: 22px;
            font-weight: bold;
            text-decoration: none;
            color: #02189f;
        }

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
            background-color: #e7e7ff;
        }

        #report-table .status-dd_status {
            background-color: #fdf0db;
        }

        #report-table .status-calc_status {
            background-color: #e7e7ff;
        }

        #report-table .status-manufactor_status {
            background-color: #fdf0db;
        }

        #report-table .status-purchase_status {
            background-color: #e7e7ff;
        }


        #report-table tbody th {
            position: static;
        }
    </style>

    <table class="table table-bordered table-hover" id="report-table">
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
                <th class="not-specified status-{{$status}}">Не указано</th>
                @foreach($statusData as $value => $label)
                    <th class="status-header status-{{$status}}">
                        {{ $label }}
                    </th>
                @endforeach
            @endforeach
        </tr>
        @foreach($report['rows'] as $row)
            <tr>
                <td class="fixed-left">{{ $row['component']->constructor?->direction?->label() }}</td>
                <td class="fixed-left component">{{ $row['component']->label() }}</td>
                <td style="font-size: 22px;  font-weight: bold; text-decoration: none;" class="value
                fixed-left">
                    <a class="data-link" target="_blank" href="{{ $filterUrl($row['component']->id) }}">{{
                    $row['total'] }}</a>
                </td>
                @foreach($report['status'] as $field => $statusData)
                    <td class="value status-{{$field}}">
                        <a class="data-link" target="_blank"
                           href="{{ $filterUrl($row['component']->id, [ $field => [0]]) }}">
                            {{$row['byStatus'][$field][0] ?? 0 }}</a>
                    </td>
                    @foreach($statusData as $value => $label)
                        <td class="value status-{{$field}}">
                            <a class="data-link" target="_blank" href="{{ $filterUrl($row['component']->id, [ $field =>
                            [$value]])
                            }}">
                                {{$row['byStatus'][$field][$value] ?? 0 }}
                            </a>
                        </td>
                    @endforeach

                @endforeach
            </tr>
        @endforeach
        <tr>
            <th class="fixed-left" colspan="2">Всего в составе</th>
            <th class="value fixed-left">
                <a href="{{ route(\App\Http\Controllers\Component\ComponentController::ROUTE_NAME,
   ['filters' => ['physical_object_id' => [$report['object']->id]]]) }}"
                   class="data-link" target="_blank">
                    {{$report['footer']['total']}}
                </a>
            </th>
            @foreach($report['status'] as $field => $statusData)
                <th class="value status-{{$field}}">
                    <a href="{{ $totalFilterUrl($field, 0) }}" class="data-link" target="_blank">
                        {{$report['footer']['status'][$field][0] ?? 0 }}
                    </a>
                </th>
                @foreach($statusData as $value => $label)
                    <th class="value status-{{$field}}">
                        <a href="{{ $totalFilterUrl($field, $value) }}" target="_blank" class="data-link">
                            {{$report['footer']['status'][$field][$value] ?? 0 }}
                        </a>

                    </th>
                @endforeach
            @endforeach
        </tr>
    </table>
@endsection
