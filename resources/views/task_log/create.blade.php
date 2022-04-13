@extends('layouts.app')

@section('title') Добавление информации о задаче@endsection

@section('content')


    <div class="container">
        <h1>Добавление данных для задачи "{{ $task->name }}" </h1>
        <form method="post">
            @csrf
            <div class="form-group">
                <label for="status">Статус проблемы</label>
                <select name="status" id="status" class="form-control {{ $errors->has('status') ? 'error' : '' }}">
                    @foreach(\App\Models\TaskLog::ALL_STATUSES as $value => $label )
                        <option value="{{ $value}}" @if( $value == old('status') ) selected @endif>
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
            <div class="form-group">
                <label for="date_refresh_plan">Дата обновления план</label>
                <input name="date_refresh_plan"
                       class="form-control {{ $errors->has('date_refresh_plan') ? 'error' : '' }}"
                       id="date_refresh_plan" type="date" value="{{ old('date_refresh_plan')  }}">
                @if ($errors->has('date_refresh_plan'))
                    <div class="error">
                        {{ $errors->first('date_refresh_plan') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="date_refresh_fact">Дата обновления план</label>
                <input name="date_refresh_fact"
                       class="form-control {{ $errors->has('date_refresh_fact') ? 'error' : '' }}"
                       id="date_refresh_fact" type="date" value="{{ old('date_refresh_fact')  }}">
                @if ($errors->has('date_refresh_fact'))
                    <div class="error">
                        {{ $errors->first('date_refresh_fact') }}
                    </div>
                @endif
            </div>


            <div class="form-group">
                <label for="trouble">Что мешает</label>
                <textarea name="trouble" class="form-control {{ $errors->has('trouble') ? 'error' : '' }}"
                          id="trouble" cols="10" rows="5" required>{{ old('trouble') }} </textarea>
                @if ($errors->has('trouble'))
                    <div class="error">
                        {{ $errors->first('trouble') }}
                    </div>
                @endif
            </div>


            <div class="form-group">
                <label for="what_to_do">Что делаем</label>
                <textarea name="what_to_do" class="form-control {{ $errors->has('what_to_do') ? 'error' : '' }}"
                          id="what_to_do" cols="10" rows="5">{{ old('what_to_do') }}</textarea>
                @if ($errors->has('what_to_do'))
                    <div class="error">
                        {{ $errors->first('what_to_do') }}
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-info mt-3">Сохранить</button>
        </form>
    </div>
@endsection
