@extends('layouts.app')
@section('title') Редактирование задачи @endsection
@section('content')

    @php

        /**
            * @var $task \App\Models\Task
            */

    @endphp


    <div class="container">
        <h3>{{ $title }}</h3>

        <div style="display: none">
            <table>
                <tr id="task-log-template">
                    <td>
                        <input type="hidden" name="task_log[__id__][id]" value="">
                        <select name="task_log[__id__][status]">
                            @foreach ( \App\Models\TaskLog::ALL_STATUSES as $value => $label)
                                <option value="{{$value}}">
                                    {{$label}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="date" name="task_log[__id__][date_refresh_plan]">
                    </td>
                    <td>
                        <input type="date" name="task_log[__id__][date_refresh_fact]">
                    </td>
                    <td>
                        <input type="text" name="task_log[__id__][trouble]" required>
                    </td>
                    <td><input type="text" name="task_log[__id__][what_to_do]">
                    </td>
                    <td>
                        <a class="btn btn-danger" onclick="deleteTaskLog(this)">Удалить</a>
                    </td>
                </tr>
            </table>
        </div>
        <form method="post">
            @csrf

            @if($fieldsToEdit === null || in_array('project', $fieldsToEdit))
                <div class="form-group">
                    <label for="project">Проект</label>
                    <select name="project[]"
                            class="select2 form-control {{ $errors->has('project') ? 'error' : '' }}"
                            id="project" multiple="multiple">

                        @foreach($projects as $project )
                            <option value="{{ $project->id }}"
                                    @if(in_array($project->id,
                                    old('project',$task->projects()->allRelatedIds()->toArray()))) selected
                                @endif>
                                {{ $project->title }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('project'))
                        <div class="error">
                            {{ $errors->first('project') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('family', $fieldsToEdit))
                <div class="form-group">
                    <label for="family">Семейство</label>
                    <select name="family[]"
                            class="select2 form-control {{ $errors->has('family') ? 'error' : '' }}"
                            id="family" multiple="multiple">
                        @foreach($families as $family )
                            <option value="{{ $family->id }}"
                                    @if(in_array($family->id,
                                    old('family',$task->families()->allRelatedIds()->toArray()))) selected
                                @endif>
                                {{ $family->title }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('family'))
                        <div class="error">
                            {{ $errors->first('family') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('product', $fieldsToEdit))

                <div class="form-group">
                    <label for="product">Продукт</label>
                    <select name="product[]" id="product" multiple
                            class=" select2 form-control {{ $errors->has('product') ? 'error' : '' }}">
                        @foreach( $products as $product )
                            <option value="{{ $product->id }}"
                                    @if(in_array($product->id,
                                    old('product',$task->products()->allRelatedIds()->toArray()))) selected
                                @endif>
                                {{ $product->title }}
                            </option>
                        @endforeach

                    </select>
                    @if ( $errors->has('product'))
                        <div class="error">
                            {{ $errors->first('product') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('base', $fieldsToEdit))

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
            @endif
            @if($fieldsToEdit === null || in_array('setting_date', $fieldsToEdit))

                <div class="form-group">
                    <label for="setting_date">Дата постановки</label>
                    <input name="setting_date"
                           class="form-control {{ $errors->has('setting_date') ? 'error' : '' }}"
                           id="setting_date" type="date"
                           value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('setting_date', $task->setting_date)) }}">
                    @if ($errors->has('setting_date'))
                        <div class="error">
                            {{ $errors->first('setting_date') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('task_creator', $fieldsToEdit))
                <div class="form-group">
                    <label for="task_creator">Постановщик</label>
                    <input name="task_creator"
                           class="form-control {{ $errors->has('task_creator') ? 'error' : '' }}"
                           id="theme" type="text" value="{{ old('task_creator', $task->task_creator)  }}">
                    @if ($errors->has('task_creator'))
                        <div class="error">
                            {{ $errors->first('task_creator') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('priority', $fieldsToEdit))
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
            @endif
            @if($fieldsToEdit === null || in_array('type', $fieldsToEdit))
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
            @endif

            @if($fieldsToEdit === null || in_array('theme', $fieldsToEdit))
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
            @endif

            @if($fieldsToEdit === null || in_array('main_task', $fieldsToEdit))
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
            @endif
            @if($fieldsToEdit === null || in_array('name', $fieldsToEdit))
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
            @endif
            @if($fieldsToEdit === null || in_array('user_id', $fieldsToEdit))
                <div class="form-group">
                    <label for="userId">Ответственный</label>
                    <select name="user_id" class="form-control {{ $errors->has('user_id') ? 'error' : '' }}"
                            id="userId" required>
                        @foreach($users as $user )
                            <option value="{{ $user->id }}"
                                    @if( $user->id == old('user_id',$task->user_id) ) selected @endif>
                                {{ $user->surname }}
                                {{ $user->name }}
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
            @endif
            @if($fieldsToEdit === null || in_array('coperformers', $fieldsToEdit))
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
            @endif


            @if($fieldsToEdit === null || in_array('end_date', $fieldsToEdit))
                <div class="form-group">
                    <label for="end_date">Дата протокол</label>
                    <input name="end_date" class="form-control {{ $errors->has('end_date') ? 'error' : '' }}"
                           id="end_date" type="date"
                           value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('end_date', $task->end_date)) }}"
                           required>
                    @if ($errors->has('end_date'))
                        <div class="error">
                            {{ $errors->first('end_date') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('end_date_plan', $fieldsToEdit))
                <div class="form-group">
                    <label for="end_date_plan">Дата окончания план</label>
                    <input name="end_date_plan" class="form-control {{ $errors->has('end_date_plan') ? 'error' : '' }}"
                           id="end_date_plan" type="date"
                           value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('end_date_plan', $task->end_date_plan))
                            }}">
                    @if ($errors->has('end_date_plan'))
                        <div class="error">
                            {{ $errors->first('end_date_plan') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('end_date_fact', $fieldsToEdit))
                <div class="form-group">
                    <label for="end_date_fact">Дата окончания факт</label>
                    <input name="end_date_fact" class="form-control {{ $errors->has('end_date_fact') ? 'error' : '' }}"
                           id="end_date_fact" type="date"
                           value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('end_date_fact', $task->end_date_fact))
                            }}">
                    @if ($errors->has('end_date_fact'))
                        <div class="error">
                            {{ $errors->first('end_date_fact') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('execute', $fieldsToEdit))
                <div class="form-group">
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
            @endif
            @if($fieldsToEdit === null || in_array('status', $fieldsToEdit))
                <div class="form-group">
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
            @endif
            @if($fieldsToEdit === null || in_array('execute_time_plan', $fieldsToEdit))
                <div class="form-group">
                    <label for="execute_time_plan">Кол-во ч/ч, план</label>
                    <input name="execute_time_plan" class="form-control {{ $errors->has('execute_time_plan') ? 'error' : '' }}"
                           id="execute_time_plan" type="number" step="0.1" value="{{ old('execute_time_plan',
                           $task->execute_time_plan)
                           }}">
                    @if ($errors->has('execute_time_plan'))
                        <div class="error">
                            {{ $errors->first('execute_time_plan') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('execute_time_fact', $fieldsToEdit))
                <div class="form-group">
                    <label for="execute_time_fact">Кол-во ч/ч, факт</label>
                    <input name="execute_time_fact" class="form-control {{ $errors->has('execute_time_fact') ? 'error' : '' }}"
                           id="execute_time_fact" type="number" step="0.1" value="{{ old('execute_time_fact',
                           $task->execute_time_fact)
                           }}">
                    @if ($errors->has('execute_time_fact'))
                        <div class="error">
                            {{ $errors->first('execute_time_fact') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('comment', $fieldsToEdit))
                <div class="form-group">
                    <label for="comment">Комментарии</label>
                    <input name="comment" class="form-control {{ $errors->has('comment') ? 'error' : '' }}"
                              id="comment" type="text" value="{{ old('comment', $task->comment)  }}">
                    @if ($errors->has('comment'))
                        <div class="error">
                            {{ $errors->first('comment') }}
                        </div>
                    @endif
                </div>
            @endif

            @include('task/task_log_edit')
            @if(count($logs) == 0)
                <h6>Препятствий для решения задачи нет.</h6>
            @endif
            <button type="button" class="btn btn-outline-success mt-2"
                    onclick="addTaskLog()"> Добавить проблему
            </button>
            </br>
            <button type="submit" class="btn btn-info mt-3">Сохранить</button>
        </form>
    </div>
@endsection
