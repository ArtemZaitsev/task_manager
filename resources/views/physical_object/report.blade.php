@extends('layouts.app')
@include('layouts.navbar')
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
            font-size: 18px;
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

        /*.first-column {*/
        /*    display: flex;*/
        /*    justify-content: space-between;*/
        /*    left: 0;*/
        /*    position: sticky;*/
        /*    background: #e5ffd6 !important;*/
        /*    z-index: 2;*/
        /*}*/

        /*.first-column > div {*/
        /*    display: flex;*/
        /*    justify-content: center;*/
        /*    align-items: center;*/
        /*}*/

        .sticky-col {
            position: -webkit-sticky;
            position: sticky;
            background: #e5ffd6 !important;
        }

        .first-col {
            /*width: 150px;*/
            /*min-width: 100px;*/
            /*max-width: 200px;*/
            width: 200px;
            min-width: 200px;
            max-width: 200px;
            left: 0;
            z-index: 2;
            background: #e5ffd6 !important;
        }

        .second-col {
            /*width: 200px;*/
            /*min-width: 200px;*/
            /*max-width: 300px;*/
            width: 200px;
            min-width: 200px;
            max-width: 200px;
            left: 200px;
            z-index: 2;
            background: #e5ffd6 !important;
        }

        .third-col {
            /*width: 250px;*/
            /*min-width: 100px;*/
            /*max-width: 500px;*/
            width: 450px;
            min-width: 450px;
            max-width: 450px;
            left: 400px;
            z-index: 2;
            background: #e5ffd6 !important;
        }

        .forth-col {
            /*width: 100px;*/
            /*min-width: 100px;*/
            /*max-width: 100px;*/
            width: 100px;
            min-width: 100px;
            max-width: 100px;
            left: 850px;
            z-index: 2;
            text-align: center;
            vertical-align: middle;
        }

        #object-title {
            /*width: 700px;*/
            /*min-width: 600px;*/
            width: 950px;
            min-width: 950px;
            left: 0;
            z-index: 2;
            background: #e5ffd6 !important;

        }

    </style>

    @include('lib.columns_form', ['url' => route
      (App\Http\Controllers\PhysicalObject\PhysicalObjectReportController::SAVE_FIELDS_ROUTE_NAME),
      'fields' => $fieldSet->getFields()])

    <table class="table table-bordered table-hover" id="report-table">
        <tr>
            <td id="object-title" class="sticky-col group-header" colspan="4" style="font-size: 22px; text-align: center;
            vertical-align: middle; background-color: #e5ffd6;">{{$report['object']->label()}}</td>
            @foreach($report['status'] as $status => $statusData)
                @if($fieldSet->getField($status)->isNeedDisplay())
                    <td class="group-header status-{{$status}}" colspan="{{ count($statusData) + 1 }}">
                        Всего {{ $report['status_titles'][$status] }}:

                        <a class="data-link" href="{{  $report['totalStatusWithoutNotRequired'][$status]['url'] }}"
                           target="_blank">
                            {{ $report['totalStatusWithoutNotRequired'][$status]['count'] ?? '' }}
                        </a>
                    </td>
                @endif
            @endforeach
        </tr>
        <form method="get">
            <tr>
                <td   class="sticky-col first-col group-header">
                    Система <br/>
                    @include($filters['system']->template(), [
                                        'filter' => $filters['system'],
                                        'filterData' => $filters['system']->templateData(request())
                                        ])
                    @include('lib.filters.filter_buttons', ['filterName' => $filters['system']->name()])

                </td>
                <td class="sticky-col second-col group-header">
                    Подсистема <br/>
                    @include($filters['subsystem']->template(), [
                                        'filter' => $filters['subsystem'],
                                        'filterData' => $filters['subsystem']->templateData(request())
                                        ])
                    @include('lib.filters.filter_buttons', ['filterName' => $filters['subsystem']->name()])
                </td>
                <td class="sticky-col third-col group-header">
                    Компонент <br/>
                    @include($filters['component']->template(), [
                                    'filter' => $filters['component'],
                                    'filterData' => $filters['component']->templateData(request())
                                    ])
                    @include('lib.filters.filter_buttons', ['filterName' => $filters['component']->name()])
                </td>
                <td class="sticky-col forth-col group-header">
                    Всего позиций
                </td>
                @foreach($report['status'] as $status => $statusData)
                    @if($fieldSet->getField($status)->isNeedDisplay())
                        <th class="not-specified status-{{$status}}"
                            style="text-align: center; vertical-align: middle; min-width: 80px;">Не
                            указано
                        </th>
                        @foreach($statusData as $value => $label)
                            <th class="status-header status-{{$status}}" style="min-width: 80px;">
                                {{ $label }}
                            </th>
                        @endforeach
                    @endif
                @endforeach
            </tr>
        </form>
        @foreach($report['rows'] as $row)
            <tr>
                <td class="sticky-col first-col">
                    {{  $row['component']->system?->label() }}
                </td>
                <td class="sticky-col second-col">
                    {{ $row['component']->subsystem?->label() }}
                </td>
                <td class="sticky-col third-col">
                    {{ $row['component']->label() }}
                </td>
                <td class="sticky-col forth-col">
                    <a class="data-link" target="_blank" href="{{ $filterUrl($row['component']->id) }}">{{
                    $row['total'] }}</a>
                </td>

                @foreach($report['status'] as $field => $statusData)
                    @if($fieldSet->getField($field)->isNeedDisplay())
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
                    @endif
                @endforeach
            </tr>
        @endforeach
        <tr>
            <td class="sticky-col first-col text-end" style="font-weight: bold; font-size: 20px;" colspan="3">Всего в
                составе</td>
{{--            <td class="sticky-col second-col"></td>--}}
{{--            <td class="sticky-col third-col">--}}
            </td>
            <td class="sticky-col forth-col">
                <a href="{{ $totalUrl }}"
                   class="data-link" target="_blank">
                    {{$report['footer']['total']}}
                </a>
            </td>


            @foreach($report['status'] as $field => $statusData)
                @if($fieldSet->getField($field)->isNeedDisplay())
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
                        </th>
                    @endforeach
                @endif

            @endforeach
        </tr>
    </table>
@endsection
