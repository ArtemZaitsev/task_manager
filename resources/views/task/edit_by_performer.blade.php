@extends('layouts.app')
@section('title') Редактирование задачи @endsection
@section('content')

    @php

        /**
            * @var $task \App\Models\Task
            */

    @endphp


    <div class="container">
        <table>
            {{--            <tr>--}}
            {{--                <th>--}}
            {{--                    Проект--}}
            {{--                </th>--}}
            {{--                <td>--}}
            {{--                    @foreach($task->projects as $project)--}}
            {{--                        {{ $project->title }}--}}
            {{--                        @if(!$loop->last)--}}
            {{--                            <br/>--}}
            {{--                        @endif--}}
            {{--                    @endforeach--}}
            {{--                </td>--}}
            {{--            </tr>--}}
            <tr>
                {{--                <th>--}}
                {{--                    Задача--}}
                {{--                </th>--}}
                {{--                <td>--}}
                <h4>{{ $task->name }}</h4>
                {{--                </td>--}}
            </tr>


        </table>

        <form method="post" action="{{ $actionUrl }}">

            @csrf
            <div class="form-group col-2 mt-2">
                <label for="execute">Приступить</label>
                <select name="execute" id="execute"
                        class="form-control {{ $errors->has('execute') ? 'error' : '' }}"
                        required>
                    @foreach(\App\Models\Task::ALL_EXECUTIONS as $value => $label )
                        <option value="{{ $value}}"
                                @if( $value == old('execute',$task->execute) ) selected @endif>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('execute'))
                    <div class="error">
                        {{ $errors->first('execute') }}
                    </div>
                @endif
            </div>


            <div class="form-group col-2 mt-2">
                <label for="status">Статус выполнения</label>

                <select name="status" id="status"
                        class="form-control {{ $errors->has('status') ? 'error' : '' }}"
                        required>
                    @foreach(\App\Models\Task::ALL_STATUSES as $value => $label )
                        <option value="{{ $value}}"
                                @if( $value == old('status',$task->status) ) selected @endif>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('status'))
                    <div class="error">
                        {{ $errors->first('status') }}
                    </div>
                @endif
            </div>
            <div class="form-group mt-2">
                <label for="comment">Комментарии</label>
                <input name="comment" class="form-control {{ $errors->has('comment') ? 'error' : '' }}"
                       id="comment" type="text" value="{{ old('comment', $task->comment)  }}">
                @if ($errors->has('comment'))
                    <div class="error">
                        {{ $errors->first('comment') }}
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-info mt-1">Сохранить вышеуказанные поля и вернуться к списку
                задач
            </button>
        </form>

        @if(count($logs) > 0)
            <table class="table table-bordered table-hover mt-5">
                <tr style="background-color: #d1f4ff ;">
                    <th>Статус решения</th>
                    <th>Дата обновления план</th>
                    <th>Дата обновления факт</th>
                    <th>Что мешает</th>
                    <th>Что делаем</th>
                    <th>Действия</th>
                </tr>
                @foreach($logs as $log)
                    <tr>
                        <td>
                            <select name="task_log[{{$log->id}}][status]">
                                @foreach ( \App\Models\TaskLog::ALL_STATUSES as $value => $label)
                                    <option value="{{$value}}"
                                            @if( $value == old("task_log.{$log->id}.status",$log->status) ) selected @endif >
                                        {{$label}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has("task_log.{$log->id}.status"))
                                <div class="error">
                                    {{ $errors->first("task_log.{$log->id}.status") }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <input type="date" name="task_log[{{$log->id}}][date_refresh_plan]"
                                   value="{{ \App\Utils\DateUtils::dateToHtmlInput($log->date_refresh_plan)  }}">
                            @if ($errors->has("task_log.{$log->id}.date_refresh_plan"))
                                <div class="error">
                                    {{ $errors->first("task_log.{$log->id}.date_refresh_plan") }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <input type="date" name="task_log[{{$log->id}}][date_refresh_fact]"
                                   value="{{ \App\Utils\DateUtils::dateToHtmlInput($log->date_refresh_fact)  }}">
                            @if ($errors->has("task_log.{$log->id}.date_refresh_fact"))
                                <div class="error">
                                    {{ $errors->first("task_log.{$log->id}.date_refresh_fact") }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <input type="text" name="task_log[{{$log->id}}][trouble]"
                                   value="{{ old("task_log.{$log->id}.trouble", $log->trouble)  }} "
                                   required>
                            @if ($errors->has("task_log.{$log->id}.trouble"))
                                <div class="error">
                                    {{ $errors->first("task_log.{$log->id}.trouble") }}
                                </div>
                            @endif
                        </td>
                        <td><input type="text" name="task_log[{{$log->id}}][what_to_do]"
                                   value="{{($log->what_to_do)}}">
                            @if ($errors->has("task_log.{$log->id}.what_to_do"))
                                <div class="error">
                                    {{ $errors->first("task_log.{$log->id}.what_to_do") }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-danger" href="{{ route
                                (App\Http\Controllers\Task\TaskLogController::DELETE_ACTION,
                                ['id' => $log->id]) }}">
                                Удалить
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>


            <a class="btn btn-outline-success mt-2"
               href="{{ route(\App\Http\Controllers\Task\TaskLogController::INDEX_ACTION,['id' => $task->id,
                   'back' => url()->current()]) }}">
                Добавить проблему к этой задаче и вернуться на эту же страницу
            </a>
        @else
            <h6>Препятствий для решения задачи нет.</h6>
            <a class="btn btn-outline-success mt-2"
               href="{{ route(\App\Http\Controllers\Task\TaskLogController::INDEX_ACTION,['id' => $task->id,
                   'back' => url()->current()]) }}">
                Добавить проблему к этой задаче и вернуться на эту же страницу
            </a>
        @endif


    </div>
@endsection
