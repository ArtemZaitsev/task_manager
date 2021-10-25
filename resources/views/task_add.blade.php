<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form method="post" action="{{ route('task.store') }}">
    @csrf
    <div class="form-group">
        <label for="name">Название задачи</label>
        <input  name="name" class="form-control {{ $errors->has('name') ? 'error' : '' }}"
                id="name" type="text" value="{{ old('name')  }}"  >
        @if ($errors->has('name'))
            <div class="error">
                {{ $errors->first('name') }}
            </div>
        @endif
    </div>

    <div class="form-group">
        <label for="startDate">Дата начала</label>
        <input name="start_date" class="form-control {{ $errors->has('startDate') ? 'error' : '' }}"
               id="startDate" type="datetime-local" value="{{ old('start_date')  }}">
        @if ($errors->has('start_date'))
            <div class="error">
                {{ $errors->first('startDate') }}
            </div>

        @endif
    </div>
    <div class="form-group">
        <label for="endDate">Дата окончания</label>
        <input name="end_date" class="form-control {{ $errors->has('endDate') ? 'error' : '' }}"
               id="endDate" type="datetime-local" value="{{ old('end_date') }}">
        @if ($errors->has('end_date'))
            <div class="error">
                {{ $errors->first('endDate') }}
            </div>
        @endif
    </div>
    </div>

    <div class="form-group">
        <label for="userId">Ответственный</label>
        <select name="user_id" class="form-control {{ $errors->has('user_id') ? 'error' : '' }}"
                id="userId">
            @foreach($users as $user )
                <option  value="{{ $user->id }}" @if( $user->id == old('user_id') ) selected @endif>
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
        <label for="parent_id">Родительская задача</label>
        <select name="parent_id" class="form-control {{ $errors->has('parent_id') ? 'error' : '' }}" id="parent_id">
            @foreach($tasks as $task)
                <option value="{{ $task->id }}"  @if( $task->id == old('parent_id') ) selected @endif>
                    {{ $task->name }}
                </option>
            @endforeach

        </select>
        @if ($errors->has('user_id'))
            <div class="error">
                {{ $errors->first('user_id') }}
            </div>
        @endif
    </div>


    <button type="submit">отправить</button>
</form>

</body>
</html>
