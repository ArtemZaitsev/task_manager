@extends('layouts.app')
@section('title') Создание задачи @endsection
@section('content')

    <div class="container">
        <form method="post" action="{{ route('task.store') }}">
            @csrf

            <div class="form-group">
                <label for="base">Основание</label>
                <input name="base" class="form-control {{ $errors->has('base') ? 'error' : '' }}"
                       id="theme" type="text" value="{{ old('base')  }}">
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
                       value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('setting_date',)) }}">
                @if ($errors->has('setting_date'))
                    <div class="error">
                        {{ $errors->first('setting_date') }}
                    </div>

                @endif
            </div>



            <div class="form-group">
                <label for="task_creator">Постановщик</label>
                <input name="task_creator" class="form-control {{ $errors->has('task_creator') ? 'error' : '' }}"
                       id="theme" type="text" value="{{ old('task_creator')  }}">
                @if ($errors->has('task_creator'))
                    <div class="error">
                        {{ $errors->first('task_creator') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="priority">Приоритет</label>
                <select name="priority" id="priority" class="form-control {{ $errors->has('priority') ? 'error' : '' }}">
                    @foreach(\App\Models\Task::All_PRIORITY as $value => $label )
                        <option value="{{ $value}}" @if( $value == old('priority') ) selected @endif>
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
                        <option value="{{ $value}}" @if( $value == old('type') ) selected @endif>
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
                       id="theme" type="text" value="{{ old('theme')  }}">
                @if ($errors->has('theme'))
                    <div class="error">
                        {{ $errors->first('theme') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="main_task">Основная задача</label>
                <input name="main_task" class="form-control {{ $errors->has('main_task') ? 'error' : '' }}"
                       id="main_task" type="text" value="{{ old('main_task')  }}">
                @if ($errors->has('main_task'))
                    <div class="error">
                        {{ $errors->first('main_task') }}
                    </div>
                @endif
            </div>


            <div class="form-group">
                <label for="name">Задача</label>
                <input name="name" class="form-control {{ $errors->has('name') ? 'error' : '' }}"
                       id="name" type="text" value="{{ old('name')  }}">
                @if ($errors->has('name'))
                    <div class="error">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="userId">Ответственный</label>
                <select name="user_id" class="form-control {{ $errors->has('user_id') ? 'error' : '' }}"
                        id="userId">
                    @foreach($users as $user )
                        <option value="{{ $user->id }}"
                                @if( $user->id == old('user_id') ) selected @endif>
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
                <label for="start_date">Дата начала</label>
                <input name="start_date" class="form-control {{ $errors->has('start_date') ? 'error' : '' }}"
                       id="start_date" type="date"
                       value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('start_date')) }}" required>
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
                       value="{{ \App\Utils\DateUtils::dateToHtmlInput(old('end_date')) }}" required>
                @if ($errors->has('end_date'))
                    <div class="error">
                        {{ $errors->first('end_date') }}
                    </div>
                @endif
            </div>


            <div class="form-group">
                <label for="execute">Приступить</label>
                <select name="execute" id="execute" class="form-control {{ $errors->has('execute') ? 'error' : '' }}">
                    @foreach(\App\Models\Task::ALL_EXECUTIONS as $value => $label )
                        <option value="{{ $value}}" @if( $value == old('execute') ) selected @endif>
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
                <select name="status" id="status" class="form-control {{ $errors->has('status') ? 'error' : '' }}">
                    @foreach(\App\Models\Task::ALL_STATUSES as $value => $label )
                        <option value="{{ $value}}" @if( $value == old('status', $task->status) ) selected @endif>
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


            <button type="submit " class="btn btn-info mt-md-3">Отправить</button>
        </form>
    </div>
@endsection
