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


        /*#report-table tbody th {*/
        /*    position: static;*/
        /*}*/

        .first-column {
            display: flex;
            justify-content: space-between;
            left: 0;
            position: sticky;
            background: #e5ffd6 !important;
            z-index: 2;
        }

        .first-column > div {
            display: flex;
            justify-content: center;
            align-items: center;
        }

    </style>

    <table class="table table-bordered table-hover" id="report-table">
        <tr>
            {{--            <th colspan="3" style="text-align: center; vertical-align: middle; background-color: #a6ff75;--}}
            {{--">{{$report['object']->label()}}</th>--}}
            <th class="first-column" style="text-align: center; vertical-align: middle; background-color: #e5ffd6;
">{{$report['object']->label()}}</th>
            @foreach($report['status'] as $status => $statusData)
                <td class="group-header status-{{$status}}" colspan="{{ count($statusData) + 1 }}">
                    Всего {{ $report['status_titles'][$status] }}:

                    {{ $report['totalStatusWithoutNotRequired'][$status] ?? '' }}
                </td>
            @endforeach
        </tr>
        <form method="get">
            <tr>
                <td class="first-column" style="font-weight: bold;height: 110px;">
                    <div
                        style="text-align: center; vertical-align: middle; min-width: 150px; border-right: 1px solid black;">
                        Направление
                    </div>
                    <div class="component" style="text-align: center; vertical-align: middle;
                min-width:400px;border-right: 1px solid black;">
                        Компонент <br/>
                        @include($filters['component']->template(), [
                                        'filter' => $filters['component'],
                                        'filterData' => $filters['component']->templateData(request())
                                        ])
                        @include('lib.filters.filter_buttons', ['filterName' => $filters['component']->name()])
                    </div>
                    <div style="text-align: center; vertical-align: middle; min-width: 80px;">Всего позиций</div>
                </td>
                @foreach($report['status'] as $status => $statusData)
                    <th class="not-specified status-{{$status}}"
                        style="text-align: center; vertical-align: middle; min-width: 80px;">Не
                        указано
                    </th>
                    @foreach($statusData as $value => $label)
                        <th class="status-header status-{{$status}}" style="min-width: 80px;">
                            {{ $label }}
                        </th>
                    @endforeach
                @endforeach
            </tr>
        </form>
        @foreach($report['rows'] as $row)
            <tr>
                <td class="first-column">
                    <div style="width: 150px;  border-right: 1px solid black;">{{
                    $row['component']->constructor?->direction?->label() }}</div>
                    <div style="width: 400px; border-right: 1px solid black;" class="component">{{ $row['component']->label
                    () }}</div>
                    <div style="width: 80px;  font-size: 22px;  font-weight: bold;
                    text-decoration: none;" class="value">
                        <a class="data-link" target="_blank" href="{{ $filterUrl($row['component']->id) }}">{{
                    $row['total'] }}</a>
                    </div>
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
            <td class="first-column">
                <div style="width: 550px; font-weight: bold; border-right: 1px solid black;">Всего в составе</div>
                <div class="value" style="width: 80px; ">
                    <a href="{{ $totalUrl }}"
                       class="data-link" target="_blank">
                        {{$report['footer']['total']}}
                    </a>
                </div>
            </td>


            @foreach($report['status'] as $field => $statusData)
                <th class="value status-{{$field}}">
                    <a href="{{ $totalFilterUrl($field, 0) }}" class="data-link" target="_blank">
                        {{$report['footer']['status'][$field][0] ?? 0 }}
                    </a>
                </th>
                @foreach($statusData as $value => $label)
                    <th class="value status-{{$field}}">
                        <a href="{{ $totalFilterUrl($field, $value) }}" target="_blank"
                           class="data-link">
                            {{$report['footer']['status'][$field][$value] ?? 0 }}
                        </a>

{{--                        <a href="{{ $totalFilterUrl($row['component']->id, [ $field => [$value]]) }}"--}}
{{--                           target="_blank" class="data-link">--}}
{{--                            {{$report['footer']['status'][$field][$value] ?? 0 }}--}}
{{--                        </a>--}}

                    </th>
                @endforeach
            @endforeach
        </tr>
    </table>
@endsection
