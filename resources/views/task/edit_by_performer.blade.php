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


            @if(count($logs) > 0)
                @include('task/task_log_edit')
            @else
                <h6>Препятствий для решения задачи нет.</h6>
            @endif
            <a class="btn btn-outline-success mt-2"
               href="{{ route(\App\Http\Controllers\Task\TaskLogController::INDEX_ACTION,['id' => $task->id,
                   'back' => url()->full()]) }}">
                Добавить проблему
            </a>
            </br>
            <button type="submit" class="btn btn-info mt-3">Сохранить</button>
        </form>

    </div>
@endsection
