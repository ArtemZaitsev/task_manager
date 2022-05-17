@extends('layouts.app')
@section('title') Список задач @endsection
@section('content')


    <div class="main">

        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success')}}
            </div>
        @endif
        <div>{{  Illuminate\Support\Facades\Auth::user()->labelFull()  }}</div>

        <div>\\enovia\Projects\UMP\01__Project_management\Exchange\Протоколы</div>

        <a href="{{ route(\App\Http\Controllers\Task\TaskController::ACTION_LIST) }}" class="btn
                        btn-success m-1">Очистить фильтры</a>
        @if($taskVoter->canCreate())
            <a class="btn btn-success m-3" href="{{ route('task.showFormAdd') }}">
                Создать задачу
            </a>
        @endif
        <a class="btn btn-info m-3" href="{{ route('task.setupColumns.show') }}">
            Настроить столбцы
        </a>
        @if($taskVoter->canExport())
            <a href="{{ $exportUrl }}" class="btn btn-warning m-1">
                Экспорт в Excel
            </a>
        @endif
        <a class="btn btn-danger m-1 " href="{{ route(\App\Http\Controllers\LoginController::LOGOUT_ACTION) }}">
            Выход из системы
        </a>

        @impersonating()
        <a class="btn btn-outline-info m-1" href="{{ route('impersonate.leave') }}">Выйти из-под
            пользователя</a>
        @endImpersonating

        <table class="table table-bordered table-hover">
            <thead class="thead-dark" style="background-color: #d1f4ff; ">
            <form method="GET">
                <tr>
                    <th scope="col" class="text-center sticky-th">
                        <div class="for-headers">
                            Управление задачей
                        </div>
                    </th>
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('project'))
                        <th scope="col" class="text-center">
                            @include('filters.entity_filter', [
                            'filter_name' => 'project',
                            'filter_data' => $projects,
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])
                            <div scope="col" class="text-center for-headers">
                                Проект
                            </div>
                        </th>
                    @endif

                    @if( \App\Utils\ColumnUtils::isColumnEnabled('family'))
                        <th scope="col" class="text-center">
                            @include('filters.entity_filter', [
                            'filter_name' => 'family',
                            'filter_data' => $families,
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])
                            <div scope="col" class="text-center for-headers">
                                Семейство
                            </div>
                        </th>
                    @endif

                    @if( \App\Utils\ColumnUtils::isColumnEnabled('product'))
                        <th scope="col" class="text-center">
                            @include('filters.entity_filter', [
                            'filter_name' => 'product',
                            'filter_data' => $products,
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])
                            <div scope="col" class="text-center for-headers">
                                Продукт
                            </div>
                        </th>
                    @endif

                    @if( \App\Utils\ColumnUtils::isColumnEnabled('direction'))
                        <th scope="col" class="text-center" style="max-width: 250px;">
                            @include('filters.entity_filter', [
                            'filter_name' => 'direction',
                            'filter_data' => $directions,
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])
                            <div scope="col" class="text-center for-headers">
                                <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                        'direction', request())  }}">Направление
                                    <?php \App\Http\Controllers\Task\TaskController::sortColumn('direction', request()) ?>
                                </a>
                            </div>
                        </th>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('group'))
                        <th scope="col" class="text-center" style="max-width: 250px;">
                            @include('filters.entity_filter', [
                            'filter_name' => 'group',
                            'filter_data' => $groups,
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])
                            <div scope="col" class="text-center for-headers">
                                <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'group', request())  }}">Группа
                                    <?php \App\Http\Controllers\Task\TaskController::sortColumn('group', request()) ?>
                                </a>
                            </div>
                        </th>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('subgroup'))
                        <th scope="col" class="text-center" style="max-width: 250px;">
                            @include('filters.entity_filter', [
                            'filter_name' => 'subgroup',
                            'filter_data' => $subgroups,
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])
                            <div scope="col" class="text-center for-headers">
                                <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'subgroup', request())  }}">Подгруппа
                                    <?php \App\Http\Controllers\Task\TaskController::sortColumn('subgroup', request()) ?>
                                </a>
                            </div>
                        </th>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('base'))
                        <th scope="col" class="text-center">
                            @include('filters.string_filter', [
                           'filter_name' => 'base',
                           'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                         ])
                            <div scope="col" class="text-center for-headers">
                                <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'base', request())  }}">Основание
                                    <?php \App\Http\Controllers\Task\TaskController::sortColumn('base', request()) ?>
                                </a>
                            </div>
                        </th>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('setting_date'))
                        <th scope="col" class="text-center">
                            @include('filters.date_filter', [
                            'filter_name' => 'setting_date',
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                           ])
                            <div scope="col" class="text-center for-headers">
                                <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'setting_date', request())  }}">Дата постановки
                                    <?php \App\Http\Controllers\Task\TaskController::sortColumn('setting_date', request()) ?>
                                </a>
                            </div>
                        </th>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('task_creator'))
                        <th scope="col" class="text-center">
                            @include('filters.string_filter', [
                           'filter_name' => 'task_creator',
                           'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                         ])
                            <div scope="col" class="text-center for-headers">
                                <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'task_creator', request())  }}">Постановщик
                                    <?php \App\Http\Controllers\Task\TaskController::sortColumn('task_creator', request()) ?>
                                </a>
                            </div>
                        </th>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('priority'))
                        <th scope="col" class="text-center">
                            @include('filters.enum_filter', [
                            'filter_name' => 'priority',
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST,
                            'filter_data' => \App\Models\Task::All_PRIORITY
                            ])
                            <div scope="col" class="text-center for-headers">
                                <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'priority', request())  }}">Приоритет
                                    <?php \App\Http\Controllers\Task\TaskController::sortColumn('priority', request()) ?>
                                </a>
                            </div>
                        </th>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('type'))
                        <th scope="col" class="text-center">
                            @include('filters.enum_filter', [
                            'filter_name' => 'type',
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST,
                            'filter_data' => \App\Models\Task::All_TYPE
                            ])
                            <div scope="col" class="text-center for-headers">
                                <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'type', request())  }}">Тип
                                    <?php \App\Http\Controllers\Task\TaskController::sortColumn('type', request()) ?>
                                </a>
                            </div>
                        </th>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('theme'))
                        <th scope="col" class="text-center">
                            @include('filters.string_filter', [
                           'filter_name' => 'theme',
                           'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                         ])
                            <div scope="col" class="text-center for-headers">
                                <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'theme', request())  }}">Тема
                                    <?php \App\Http\Controllers\Task\TaskController::sortColumn('theme', request()) ?>
                                </a>
                            </div>
                        </th>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('main_task'))
                        <th scope="col" class="text-center">
                            @include('filters.string_filter', [
                              'filter_name' => 'main_task',
                              'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])
                            <div scope="col" class="text-center for-headers">
                                <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'main_task', request())  }}">Основная задача
                                    <?php \App\Http\Controllers\Task\TaskController::sortColumn('main_task', request()) ?>
                                </a>
                            </div>
                        </th>
                    @endif

                    <th scope="col" class="text-center">
                        @include('filters.string_filter', [
                            'filter_name' => 'task_name',
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])
                        <div scope="col" class="text-center for-headers" style="min-width: 500px">
                            <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'name', request())  }}">Задача
                                <?php \App\Http\Controllers\Task\TaskController::sortColumn('name', request()) ?>
                            </a>
                        </div>

                    </th>
                    <th scope="col" class="text-center">
                        @include('filters.entity_filter', [
                            'filter_name' => 'user',
                            'filter_data' => $users,
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])

                        <div scope="col" class="text-center for-headers">
                            <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'user', request())  }}">Ответственный
                                <?php \App\Http\Controllers\Task\TaskController::sortColumn('user', request()) ?>
                            </a>
                        </div>
                    </th>

                    <th scope="col" class="text-center">
                        @include('filters.entity_filter', [
                            'filter_name' => 'coperformer',
                            'filter_data' => $users,
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])
                        <div scope="col" class="text-center for-headers">
                            Соисполнители
                        </div>
                    </th>

                    <th scope="col" class="text-center ">
                        @include('filters.date_filter', [
                            'filter_name' => 'end_date',
                              'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])

                        <div scope="col" class="text-center for-headers">
                            <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'end_date', request())  }}">Дата протокол
                                <?php \App\Http\Controllers\Task\TaskController::sortColumn('end_date', request()) ?>
                            </a>
                        </div>
                    </th>
                    <th scope="col" class="text-center ">
                        @include('filters.date_filter', [
                            'filter_name' => 'end_date_plan',
                              'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])

                        <div scope="col" class="text-center for-headers">
                            <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'end_date_plan', request())  }}">Дата окончания план
                                <?php \App\Http\Controllers\Task\TaskController::sortColumn('end_date_plan', request()
                                ) ?>
                            </a>
                        </div>
                    </th>
                    <th scope="col" class="text-center ">
                        @include('filters.date_filter', [
                            'filter_name' => 'end_date_fact',
                              'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])

                        <div scope="col" class="text-center for-headers">
                            <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'end_date_fact', request())  }}">Дата окончания факт
                                <?php \App\Http\Controllers\Task\TaskController::sortColumn('end_date_fact', request()
                                ) ?>
                            </a>
                        </div>
                    </th>
                    <th scope="col" class="text-center">
                        @include('filters.enum_filter', [
                            'filter_name' => 'execute',
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST,
                            'filter_data' => \App\Models\Task::ALL_EXECUTIONS
                            ])
                        <div scope="col" class="text-center for-headers">
                            @include('sort_field', [
                                'sortColumn' => 'execute',
                                'label' => 'Приступить'
                            ])
                        </div>
                    </th>

                    <th scope="col" class="text-center">
                        @include('filters.enum_filter', [
                            'filter_name' => 'status',
                            'filter_data' => \App\Models\Task::ALL_STATUSES,
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])
                        <div class="text-center for-headers">
                            <a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,
                    'status', request())  }}">Статус выполнения
                                <?php \App\Http\Controllers\Task\TaskController::sortColumn('status', request()) ?>
                            </a>
                        </div>
                    </th>
                    <th scope="col" class="text-center">
                        @include('filters.string_filter', [
                            'filter_name' => 'comment',
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])
                        <div scope="col" class="text-center for-headers">Комментарии</div>
                    </th>
                    <th scope="col" class="text-center">
                        @include('filters.enum_filter', [
                            'filter_name' => 'task_log_status',
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST,
                            'filter_data' => \App\Models\TaskLog::ALL_STATUSES
                            ])
                        <div scope="col" class="text-center for-headers">Статус проблемы</div>
                    </th>

                    <th scope="col" class="text-center">
                        @include('filters.date_filter', [
                            'filter_name' => 'date_refresh_plan',
                              'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])
                        <div scope="col" class="text-center for-headers">Дата обновления проблемы план</div>
                    </th>

                    <th scope="col" class="text-center">
                        @include('filters.date_filter', [
                           'filter_name' => 'date_refresh_fact',
                             'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                           ])
                        <div scope="col" class="text-center for-headers">Дата обновления проблемы факт</div>
                    </th>

                    <th scope="col" class="text-center">
                        @include('filters.string_filter', [
                            'filter_name' => 'trouble',
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])
                        <div scope="col" class="text-center for-headers">Что мешает</div>
                    </th>
                    <th scope="col" class="text-center">
                        @include('filters.string_filter', [
                            'filter_name' => 'what_to_do',
                            'route_name' => \App\Http\Controllers\Task\TaskController::ACTION_LIST
                            ])
                        <div scope="col" class="text-center for-headers">Что делаем</div>
                    </th>


                </tr>

            </form>
            </thead>

            <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <th  @if ( count($task->logs) > 1 ) rowspan="{{ count
                    ($task->logs) }} "
                        @endif class="align-middle " style="background-color: #f6fdff;">
                        @if($taskVoter->canEdit($task))
                            <a style="text-decoration: none;" href="{{ route
                                (\App\Http\Controllers\Task\TaskEditController::EDIT_ACTION,
                                            ['id' => $task->id, 'back' => url()->full()]) }}">
                                <button type="button" class="btn btn-outline-warning" title="Редактировать">
                                    <svg width="16" height="16" fill="currentColor"
                                         class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                    </svg>
                                </button>
                            </a>
                        @endif
                        @if($taskVoter->canDelete($task))

                            <a style="text-decoration: none" onclick="return confirm('Точно удалить?')"
                               href="{{ route('task.del',['id' => $task->id]) }}">
                                <button type="button" class="btn btn-outline-danger" title="Удалить">
                                    <svg width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                                        <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
                                    </svg>
                                </button>
                            </a>

                        @endif
                    </th>
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('project'))
                        <td class="text-left align-middle"
                            @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                            @foreach($task->projects as $project)
                                {{ $project->title }}
                                @if(!$loop->last)
                                    <br/>
                                @endif
                            @endforeach
                        </td>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('family'))
                        <td class="text-left align-middle"
                            @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                            @foreach($task->families as $family)
                                {{ $family->title }}
                                @if(!$loop->last)
                                    <br/>
                                @endif
                            @endforeach
                        </td>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('product'))
                        <td class="text-left align-middle"
                            @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                            @foreach($task->products as $product)
                                {{ $product->title }}
                                @if(!$loop->last)
                                    <br/>
                                @endif
                            @endforeach
                        </td>
                    @endif

                    @if( \App\Utils\ColumnUtils::isColumnEnabled('direction'))
                        <td class="text-left align-middle"
                            @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                            {{ $task->user?->direction?->title }}
                        </td>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('group'))
                        <td class="text-left align-middle"
                            @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                            {{ $task->user?->group?->title }}
                        </td>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('subgroup'))
                        <td class="text-left align-middle"
                            @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                            {{ $task->user?->subgroup?->title }}
                        </td>
                    @endif

                    @if( \App\Utils\ColumnUtils::isColumnEnabled('base'))
                        <td class="text-left align-middle"
                            @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                            {{ $task->base }}
                        </td>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('setting_date'))
                        <td class="text-center align-middle"
                            @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                            {{ \App\Utils\DateUtils::dateToDisplayFormat($task->setting_date) }}
                        </td>
                    @endif
                    @if( \App\Utils\ColumnUtils::isColumnEnabled('task_creator'))
                        <td class="text-left align-middle"
                            @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>{{
                        $task->task_creator }}
                        </td>
                    @endif

                    @if( \App\Utils\ColumnUtils::isColumnEnabled('priority'))
                        @include('task_priority', [
                            'priority' => $task->priority,
                            ])
                    @endif

                    @if( \App\Utils\ColumnUtils::isColumnEnabled('type'))
                        @include('task_type', [
                            'type' => $task->type,
                            ])
                    @endif

                    @if( \App\Utils\ColumnUtils::isColumnEnabled('theme'))
                        <td class="text-left align-middle"
                            @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>{{ $task->theme }}
                        </td>
                    @endif

                    @if( \App\Utils\ColumnUtils::isColumnEnabled('main_task'))
                        <td class="text-left align-middle"
                            @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>{{ $task->main_task }}
                        </td>
                    @endif
                    <td class="text-left align-middle"
                        @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>{{ $task->name }}
                    </td>
                    <td class="text-left align-middle"
                        @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                        {{ $task->user->label() }}
                    </td>
                    <td class="text-left align-middle"
                        @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                        @foreach($task->coperformers as $coperformer)
                            {{ $coperformer->label() }}
                            @if(!$loop->last)
                                <br>
                            @endif
                        @endforeach
                    </td>

                    <td class="text-center align-middle"
                        @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                        {{ \App\Utils\DateUtils::dateToDisplayFormat($task->end_date) }}
                    </td>
                    <td class="text-center align-middle"
                        @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                        {{ \App\Utils\DateUtils::dateToDisplayFormat($task->end_date_plan) }}
                    </td>
                    <td class="text-center align-middle"
                        @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                        {{ \App\Utils\DateUtils::dateToDisplayFormat($task->end_date_fact) }}
                    </td>
                    <td class="text-left align-middle"
                        @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                        {{ $task->execute === null ? '' : \App\Models\Task::ALL_EXECUTIONS[$task->execute] }}</td>
                    {{--                    <td class="text-left align-middle">{{ $task->progress }}</td>--}}
                    @include('task_status', [
                           'status' => $task->status,
                           ])

                    <td class="text-left align-middle"
                        @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
                        {{ $task->comment }}

                    </td>
                    <td class="text-center align-middle">
                        @if ( count($task->logs) > 0 )
                            {{ \App\Models\TaskLog::ALL_STATUSES[$task->logs[0]->status]}}
                        @endif
                    </td>
                    <td class="text-center align-middle">
                        @if( count($task->logs) > 0 )
                            {{ \App\Utils\DateUtils::dateToDisplayFormat($task->logs[0]->date_refresh_plan) }}
                        @endif
                    </td>
                    <td class="text-center align-middle">@if ( count($task->logs) > 0 )
                            {{ \App\Utils\DateUtils::dateToDisplayFormat($task->logs[0]->date_refresh_fact) }}@endif
                    </td>
                    <td class="text-left align-middle">@if ( count($task->logs) > 0 )  {{ $task->logs[0]->trouble}}
                        @endif</td>
                    <td class="text-left align-middle">@if ( count($task->logs) > 0 )  {{ $task->logs[0]->what_to_do}} @endif</td>
                </tr>

                @if( count($task->logs) > 1 )
                    @foreach( $task->logs->slice(1) as $taskLog )
                        <tr>
                            <td class="text-center align-middle">{{
                            \App\Models\TaskLog::ALL_STATUSES[$taskLog->status]}}</td>
                            <td class="text-center align-middle">{{ App\Utils\DateUtils::dateToDisplayFormat($taskLog->date_refresh_plan) }}</td>
                            <td class="text-center align-middle">{{ App\Utils\DateUtils::dateToDisplayFormat($taskLog->date_refresh_fact) }}</td>
                            <td class="text-left align-middle">{{ $taskLog->trouble}}</td>
                            <td class="text-left align-middle">{{ $taskLog->what_to_do}}</td>
                        </tr>
                    @endforeach
                @endif


            @endforeach


            </tbody>
        </table>
        {{ $tasks->links() }}
    </div>
@endsection
