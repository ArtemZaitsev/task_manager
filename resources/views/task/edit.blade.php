@extends('layouts.app')
@section('title') Редактирование задачи @endsection
@section('content')

    @php

        /**
            * @var $task \App\Models\Task
            */

    @endphp


    <div class="container">
        <h1>Редактирование задачи "{{ $task->name }}" </h1>
        {{--                {{ dump($errors) }}--}}
        <form method="post" action="{{ route('task.edit',['id' => $task->id]) }}">
            @csrf

            <div class="form-group">
                <label for="product_id">Продукт</label>
                <select name="product_id" id="product_id"
                        class="form-control {{ $errors->has('product_id') ? 'error' : '' }}">
                    @foreach( $products as $product )
                        <option value="{{ $product->id }}"
                                @if( $product->id == old('product_id',$task->product_id) ) selected @endif>
                            {{ $product->title }}
                        </option>
                    @endforeach

                </select>
                @if ( $errors->has('product_id'))
                    <div class="error">
                        {{ $errors->first('product_id') }}
                    </div>
                @endif
            </div>


            <div class="form-group">
                <label for="base">Основание</label>
                <input name="base" class="form-control {{ $errors->has('base') ? 'error' : '' }}"
                       id="base" type="text" value="{{ old('base', $task->base)  }}">
                @if ($errors->has('base'))
                    <div class="error">
                        {{ $errors->first('base') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="setting_date">Дата постановки</label>
                <input name="setting_date" class="form-control {{ $errors->has('setting_date') ? 'error' : '' }}"
                       id="setting_date" type="date"
                       value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('setting_date', $task->setting_date)) }}">
                @if ($errors->has('setting_date'))
                    <div class="error">
                        {{ $errors->first('setting_date') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="task_creator">Постановщик</label>
                <input name="task_creator" class="form-control {{ $errors->has('task_creator') ? 'error' : '' }}"
                       id="theme" type="text" value="{{ old('task_creator', $task->task_creator)  }}">
                @if ($errors->has('task_creator'))
                    <div class="error">
                        {{ $errors->first('task_creator') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="priority">Приоритет</label>
                <select name="priority" id="priority"
                        class="form-control {{ $errors->has('priority') ? 'error' : '' }}">
                    @foreach(\App\Models\Task::All_PRIORITY as $value => $label )
                        <option value="{{ $value}}" @if( $value == old('priority', $task->priority) ) selected
                            @endif>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('priority'))
                    <div class="error">
                        {{ $errors->first('priority') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="type">Тип</label>
                <select name="type" id="type" class="form-control {{ $errors->has('type') ? 'error' : '' }}">
                    @foreach(\App\Models\Task::All_TYPE as $value => $label )
                        <option value="{{ $value}}" @if( $value == old('type', $task->type) ) selected @endif>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('type'))
                    <div class="error">
                        {{ $errors->first('type') }}
                    </div>
                @endif
            </div>


            <div class="form-group">
                <label for="theme">Тема</label>
                <input name="theme" class="form-control {{ $errors->has('theme') ? 'error' : '' }}"
                       id="theme" type="text" value="{{ old('theme', $task->theme)  }}">
                @if ($errors->has('theme'))
                    <div class="error">
                        {{ $errors->first('theme') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="main_task">Основная задача</label>
                <input name="main_task" class="form-control {{ $errors->has('main_task') ? 'error' : '' }}"
                       id="main_task" type="text" value="{{ old('main_task', $task->main_task)  }}">
                @if ($errors->has('main_task'))
                    <div class="error">
                        {{ $errors->first('main_task') }}
                    </div>
                @endif
            </div>


            <div class="form-group">
                <label for="name">Задача</label>
                <input name="name" class="form-control {{ $errors->has('name') ? 'error' : '' }}"
                       id="name" type="text" value="{{ old('name', $task->name)  }}" required>
                @if ($errors->has('name'))
                    <div class="error">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="userId">Ответственный</label>
                <select name="user_id" class="form-control {{ $errors->has('user_id') ? 'error' : '' }}"
                        id="userId" required>
                    @foreach($users as $user )
                        <option value="{{ $user->id }}"
                                @if( $user->id == old('user_id',$task->user_id) ) selected @endif>
                            {{ $user->name }}
                            {{ $user->surname }}
                            {{ $user->patronymic }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('user_id'))
                    <div class="error">
                        {{ $errors->first('user_id') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="coperformers">Соисполнители</label>
                <select name="coperformers[]"
                        class="select2 form-control {{ $errors->has('coperformers') ? 'error' : '' }}"
                        id="coperformers" multiple="multiple">
                    @foreach($users as $user )
                        <option value="{{ $user->id }}"
                            @if(in_array($user->id,
                            old('coperformers',$task->coperformers()->allRelatedIds()->toArray()))) selected
                            @endif>
                            {{ $user->name }}
                            {{ $user->surname }}
                            {{ $user->patronymic }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('coperformers'))
                    <div class="error">
                        {{ $errors->first('coperformers') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="start_date">Дата начала</label>
                <input name="start_date" class="form-control {{ $errors->has('start_date') ? 'error' : '' }}"
                       id="start_date" type="date"
                       value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('start_date', $task->start_date)) }}" required>
                @if ($errors->has('start_date'))
                    <div class="error">
                        {{ $errors->first('start_date') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="end_date">Дата окончания</label>
                <input name="end_date" class="form-control {{ $errors->has('end_date') ? 'error' : '' }}"
                       id="end_date" type="date"
                       value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('end_date', $task->end_date)) }}" required>
                @if ($errors->has('end_date'))
                    <div class="error">
                        {{ $errors->first('end_date') }}
                    </div>
                @endif
            </div>


            <div class="form-group">
                <label for="execute">Приступить</label>
                <select name="execute" id="execute" class="form-control {{ $errors->has('execute') ? 'error' : '' }}"
                        required>
                    @foreach(\App\Models\Task::ALL_EXECUTIONS as $value => $label )
                        <option value="{{ $value}}" @if( $value == old('execute',$task->execute) ) selected @endif>
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

            <div class="form-group">
                <label for="status">Статус выполнения</label>
                <select name="status" id="status" class="form-control {{ $errors->has('status') ? 'error' : '' }}"
                        required>
                    @foreach(\App\Models\Task::ALL_STATUSES as $value => $label )
                        <option value="{{ $value}}" @if( $value == old('status',$task->status) ) selected @endif>
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

            @if(count($logs) > 0)
                <table class="table table-bordered table-hover mt-5">
                    <tr style="background-color: #d1f4ff ;">
                        <th>Статус решения</th>
                        <th>Дата обновления план</th>
                        <th>Дата обновления факт</th>
                        <th>Что мешает</th>
                        <th>Что делаем</th>
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
                                       value="{{ old("task_log.{$log->id}.trouble", $log->trouble)  }} " required>
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
                        </tr>
                    @endforeach

                </table>
            @else
                <h4>Препятствий для решения задачи нет.</h4>
            @endif

            <button type="submit" class="btn btn-info mt-md-3">Сохранить</button>
        </form>
    </div>
@endsection
