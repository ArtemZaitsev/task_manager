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
                        <textarea name="task_log[__id__][trouble]" style="width: 100%;" rows="3" required></textarea>
                    </td>
                    <td>
                        <textarea name="task_log[__id__][what_to_do]" style="width: 100%;" rows="3"></textarea>
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
                            class="select2 form-control {{ $errors->has('project') ? 'is-invalid' : '' }}"
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
                        <div class="invalid-feedback">
                            {{ $errors->first('project') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('family', $fieldsToEdit))
                <div class="form-group mt-2">
                    <label for="family">Семейство</label>
                    <select name="family[]"
                            class="select2 form-control {{ $errors->has('family') ? 'is-invalid' : '' }}"
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
                        <div class="invalid-feedback">
                            {{ $errors->first('family') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('product', $fieldsToEdit))

                <div class="form-group mt-2">
                    <label for="product">Продукт</label>
                    <select name="product[]" id="product" multiple
                            class=" select2 form-control {{ $errors->has('product') ? 'is-invalid' : '' }}">
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
                        <div class="invalid-feedback">
                            {{ $errors->first('product') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('physical_objects', $fieldsToEdit))
                <div class="form-group mt-2">
                    <label for="physical_object_id">Объект</label>
                    <select name="physical_object_id"
                            class="select2 form-control {{ $errors->has('physical_object_id') ? 'is-invalid' : '' }}"
                            id="physical_object_id">
                        <option value=""></option>
                        @foreach($physical_objects as $entity )
                            <option value="{{ $entity->id }}"
                                    @if($entity->id === old('physical_object_id',$task->physical_object_id)) selected @endif>
                                {{ $entity->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('physical_object_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('physical_object_id') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('system_id', $fieldsToEdit))
                <div class="form-group mt-2">
                    <label for="system_id">Система</label>
                    <select name="system_id"
                            class="select2 form-control {{ $errors->has('system_id') ? 'is-invalid' : '' }}"
                            id="system_id">
                        <option value=""></option>
                        @foreach($systems as $entity )
                            <option value="{{ $entity->id }}"
                                    @if($entity->id === old('system_id',$task->system_id)) selected @endif>
                                {{ $entity->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('system_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('system_id') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('subsystem_id', $fieldsToEdit))
                <div class="form-group mt-2">
                    <label for="subsystem_id">Подсистема</label>
                    <select name="subsystem_id"
                            class="select2 form-control {{ $errors->has('subsystem_id') ? 'is-invalid' : '' }}"
                            id="subsystem_id">
                        <option value=""></option>
                        @foreach($subsystems as $entity )
                            <option value="{{ $entity->id }}"
                                    @if($entity->id === old('subsystem_id',$task->subsystem_id)) selected @endif>
                                {{ $entity->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('subsystem_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('subsystem_id') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('detail_id', $fieldsToEdit))
                <div class="form-group mt-2">
                    <label for="detail_id">Деталь</label>
                    <select name="detail_id"
                            class="select2 form-control {{ $errors->has('detail_id') ? 'is-invalid' : '' }}"
                            id="detail_id">

                        @foreach($details as $entity )
                            <option value="{{ $entity->id }}"
                                    @if($entity->id === old('detail_id',$task->detail_id)) selected @endif>
                                {{ $entity->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('detail_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('detail_id') }}
                        </div>
                    @endif
                </div>
            @endif


            @if($fieldsToEdit === null || in_array('base', $fieldsToEdit))

                <div class="form-group mt-2">
                    <label for="base">Основание</label>
                    <input name="base" class="form-control {{ $errors->has('base') ? 'is-invalid' : '' }}"
                           id="base" type="text" value="{{ old('base', $task->base)  }}">
                    @if ($errors->has('base'))
                        <div class="invalid-feedback">
                            {{ $errors->first('base') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('setting_date', $fieldsToEdit))

                <div class="form-group w-25 mt-2">
                    <label for="setting_date">Дата постановки</label>
                    <input name="setting_date"
                           class="form-control {{ $errors->has('setting_date') ? 'is-invalid' : '' }}"
                           id="setting_date" type="date"
                           value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('setting_date', $task->setting_date)) }}">
                    @if ($errors->has('setting_date'))
                        <div class="invalid-feedback">
                            {{ $errors->first('setting_date') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('task_creator', $fieldsToEdit))
                <div class="form-group mt-2 w-50">
                    <label for="task_creator">Постановщик</label>
                    <input name="task_creator"
                           class="form-control {{ $errors->has('task_creator') ? 'is-invalid' : '' }}"
                           id="theme" type="text" value="{{ old('task_creator', $task->task_creator)  }}">
                    @if ($errors->has('task_creator'))
                        <div class="invalid-feedback">
                            {{ $errors->first('task_creator') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('priority', $fieldsToEdit))
                <div class="form-group mt-2 w-25">
                    <label for="priority">Приоритет</label>
                    <select name="priority" id="priority"
                            class="form-control {{ $errors->has('priority') ? 'is-invalid' : '' }}">
                        @foreach(\App\Models\Task::All_PRIORITY as $value => $label )
                            <option value="{{ $value}}" @if( $value == old('priority', $task->priority) ) selected
                                @endif>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('priority'))
                        <div class="invalid-feedback">
                            {{ $errors->first('priority') }}
                        </div>
                    @endif
                </div>
            @endif

        @if($fieldsToEdit === null || in_array('type', $fieldsToEdit))
                <div class="form-group w-25 mt-2">
                    <label for="type">Тип</label>
                    <select name="type" id="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}">
                        @foreach(\App\Models\Task::All_TYPE as $value => $label )
                            <option value="{{ $value}}" @if( $value == old('type', $task->type) ) selected @endif>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('type'))
                        <div class="invalid-feedback">
                            {{ $errors->first('type') }}
                        </div>
                    @endif
                </div>
            @endif



            @if($fieldsToEdit === null || in_array('theme', $fieldsToEdit))
                <div class="form-group mt-2">
                    <label for="theme">Тема</label>
                    <input name="theme" class="form-control {{ $errors->has('theme') ? 'is-invalid' : '' }}"
                           id="theme" type="text" value="{{ old('theme', $task->theme)  }}">
                    @if ($errors->has('theme'))
                        <div class="invalid-feedback">
                            {{ $errors->first('theme') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('main_task', $fieldsToEdit))
                <div class="form-group mt-2">
                    <label for="main_task">Основная задача</label>
                    <input name="main_task" class="form-control {{ $errors->has('main_task') ? 'is-invalid' : '' }}"
                           id="main_task" type="text" value="{{ old('main_task', $task->main_task)  }}">
                    @if ($errors->has('main_task'))
                        <div class="invalid-feedback">
                            {{ $errors->first('main_task') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('parent_id', $fieldsToEdit))
                <div class="form-group w-50 mt-2">
                    <label for="parent_id">Родительская задача</label>
                    <select name="parent_id" class="select2 form-control {{ $errors->has('parent_id') ? 'is-invalid'
                    : '' }}" id="parent_id">
                        @foreach($tasks as $item)
                            <option value="{{ $item->id }}"
                                    @if( $item->id == old('parent_id',$task->parent_id) ) selected @endif>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('parent_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('parent_id') }}
                        </div>
                    @endif
                </div>
            @endif


            @if($fieldsToEdit === null || in_array('prev_tasks', $fieldsToEdit))
                <div class="form-group w-50 mt-2">
                    <label for="prev_tasks">Предыдущие задачи</label>
                    <select name="prev_tasks[]" class="select2 form-control {{ $errors->has('prev_tasks') ? 'is-invalid'
                    : ''
                     }}" id="prev_tasks" multiple>
                        @foreach($tasks as $item )
                            <option value="{{ $item->id }}"
                                    @if(in_array($item->id,
                                   old('prev_tasks',$task->prev()->allRelatedIds()->toArray()))) selected @endif>

                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('prev_tasks'))
                        <div class="invalid-feedback">
                            {{ $errors->first('prev_tasks') }}
                        </div>
                    @endif
                </div>
            @endif


            @if($fieldsToEdit === null || in_array('name', $fieldsToEdit))
                <div class="form-group mt-2">
                    <label for="name">Задача</label>
                    <textarea name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                              id="name" rows="3">{{ old('name', $task->name) }}</textarea>
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('user_id', $fieldsToEdit))
                <div class="form-group w-50 mt-2">
                    <label for="userId">Ответственный</label>
                    <select name="user_id" class="form-control {{ $errors->has('user_id') ? 'is-invalid' : '' }}"
                            id="userId" required>
                        @foreach($users as $user )
                            <option value="{{ $user->id }}"
                                    @if( $user->id == old('user_id',$task->user_id) ) selected @endif>
                                {{ $user->labelFull() }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('user_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('user_id') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('coperformers', $fieldsToEdit))
                <div class="form-group mt-2">
                    <label for="coperformers">Соисполнители</label>
                    <select name="coperformers[]"
                            class="select2 form-control {{ $errors->has('coperformers') ? 'is-invalid' : '' }}"
                            id="coperformers" multiple="multiple">
                        @foreach($users as $user )
                            <option value="{{ $user->id }}"
                                    @if(in_array($user->id,
                                    old('coperformers',$task->coperformers()->allRelatedIds()->toArray()))) selected
                                @endif>
                                {{ $user->label }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('coperformers'))
                        <div class="invalid-feedback">
                            {{ $errors->first('coperformers') }}
                        </div>
                    @endif
                </div>
            @endif



            @if($fieldsToEdit === null || in_array('end_date', $fieldsToEdit))
                <div class="form-group w-25 mt-2">
                    <label for="end_date">Дата установленная руководителем</label>
                    <input name="end_date" class="form-control {{ $errors->has('end_date') ? 'is-invalid' : ''
                     }}"
                           id="end_date" type="date"
                           value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('end_date', $task->end_date)) }}">
                    @if ($errors->has('end_date'))
                        <div class="invalid-feedback">
                            {{ $errors->first('end_date') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('start_date', $fieldsToEdit))
                <div class="form-group w-25 mt-2">
                    <label for="start_date">Дата начала план</label>
                    <input name="start_date" class="form-control {{ $errors->has('start_date') ? 'is-invalid' : ''
                     }}"
                           id="start_date" type="date"
                           value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('start_date', $task->start_date)) }}">
                    @if ($errors->has('start_date'))
                        <div class="invalid-feedback">
                            {{ $errors->first('start_date') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('end_date_plan', $fieldsToEdit))
                <div class="form-group w-25 mt-2">
                    <label for="end_date_plan">Дата окончания план</label>
                    <input name="end_date_plan"
                           class="form-control {{ $errors->has('end_date_plan') ? 'is-invalid' : '' }}"
                           id="end_date_plan" type="date"
                           value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('end_date_plan', $task->end_date_plan))
                            }}">
                    @if ($errors->has('end_date_plan'))
                        <div class="invalid-feedback">
                            {{ $errors->first('end_date_plan') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('end_date_fact', $fieldsToEdit))
                <div class="form-group w-25 mt-2">
                    <label for="end_date_fact">Дата окончания факт</label>
                    <input name="end_date_fact"
                           class="form-control {{ $errors->has('end_date_fact') ? 'is-invalid' : '' }}"
                           id="end_date_fact" type="date"
                           value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('end_date_fact', $task->end_date_fact))
                            }}">
                    @if ($errors->has('end_date_fact'))
                        <div class="invalid-feedback">
                            {{ $errors->first('end_date_fact') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('progress', $fieldsToEdit))
                <div class="form-group w-25 mt-2">
                    <label for="progress">% выполнения</label>
                    <input name="progress" class="form-control {{ $errors->has('progress') ? 'is-invalid' : '' }}"
                           id="progress" type="number" value="{{ old('progress', $task->progress)  }}" min="0"
                           max="100" step="1">
                    @if ($errors->has('progress'))
                        <div class="invalid-feedback">
                            {{ $errors->first('progress') }}
                        </div>
                    @endif
                </div>
            @endif

            @if($fieldsToEdit === null || in_array('execute', $fieldsToEdit))
                <div class="form-group w-25 mt-2">
                    <label for="execute">Приступить</label>
                    <select name="execute" id="execute"
                            class="form-control {{ $errors->has('execute') ? 'is-invalid' : '' }}"
                            required>
                        @foreach(\App\Models\Task::ALL_EXECUTIONS as $value => $label )
                            <option value="{{ $value}}"
                                    @if( $value == old('execute',$task->execute) ) selected @endif>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('execute'))
                        <div class="invalid-feedback">
                            {{ $errors->first('execute') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('status', $fieldsToEdit))
                <div class="form-group w-25 mt-2">
                    <label for="status">Статус выполнения</label>
                    <select name="status" id="status"
                            class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}"
                            required>
                        @foreach(\App\Models\Task::ALL_STATUSES as $value => $label )
                            <option value="{{ $value}}"
                                    @if( $value == old('status',$task->status) ) selected @endif>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('status'))
                        <div class="invalid-feedback">
                            {{ $errors->first('status') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('execute_time_plan', $fieldsToEdit))
                <div class="form-group w-25 mt-2">
                    <label for="execute_time_plan">Кол-во ч/ч, план</label>
                    <input name="execute_time_plan"
                           class="form-control {{ $errors->has('execute_time_plan') ? 'is-invalid' : '' }}"
                           id="execute_time_plan" type="number" step="0.1" value="{{ old('execute_time_plan',
                           $task->execute_time_plan)
                           }}">
                    @if ($errors->has('execute_time_plan'))
                        <div class="invalid-feedback">
                            {{ $errors->first('execute_time_plan') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('execute_time_fact', $fieldsToEdit))
                <div class="form-group w-25 mt-2">
                    <label for="execute_time_fact">Кол-во ч/ч, факт</label>
                    <input name="execute_time_fact"
                           class="form-control {{ $errors->has('execute_time_fact') ? 'is-invalid' : '' }}"
                           id="execute_time_fact" type="number" step="0.1" value="{{ old('execute_time_fact',
                           $task->execute_time_fact)
                           }}">
                    @if ($errors->has('execute_time_fact'))
                        <div class="invalid-feedback">
                            {{ $errors->first('execute_time_fact') }}
                        </div>
                    @endif
                </div>
            @endif
            @if($fieldsToEdit === null || in_array('comment', $fieldsToEdit))
                <div class="form-group mt-2">
                    <label for="comment">Комментарии</label>
                    <textarea name="comment" class="form-control {{ $errors->has('comment') ? 'is-invalid' : '' }}"
                              id="comment" rows="3">{{ old('comment', $task->comment)  }}</textarea>
                    @if ($errors->has('comment'))
                        <div class="invalid-feedback">
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
