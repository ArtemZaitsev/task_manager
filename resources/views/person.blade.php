<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Пользователи</title>
</head>
<body>

{{--<form method="post" action="{{ route('task.store') }}">--}}
{{--    @csrf--}}
{{--    <div class="form-group">--}}
{{--        <label for="name">Название задачи</label>--}}
{{--        <input name="name" class="form-control {{ $errors->has('name') ? 'error' : '' }}" id="name" type="text">--}}
{{--        @if ($errors->has('name'))--}}
{{--            <div class="error">--}}
{{--                {{ $errors->first('name') }}--}}
{{--            </div>--}}
{{--        @endif--}}
{{--    </div>--}}

{{--    <div class="form-group">--}}
{{--        <label for="startDate">Дата начала</label>--}}
{{--        <input name="startDate" class="form-control {{ $errors->has('startDate') ? 'error' : '' }}" id="startDate" type="datetime-local">--}}
{{--        @if ($errors->has('startDate'))--}}
{{--            <div class="error">--}}
{{--                {{ $errors->first('startDate') }}--}}
{{--            </div>--}}
{{--        @endif--}}
{{--    </div>--}}
{{--    <div class="form-group">--}}
{{--        <label for="endDate">Дата окончания</label>--}}
{{--        <input name="endDate" class="form-control {{ $errors->has('endDate') ? 'error' : '' }}" id="endDate" type="datetime-local">--}}
{{--        @if ($errors->has('endDate'))--}}
{{--            <div class="error">--}}
{{--                {{ $errors->first('endDate') }}--}}
{{--            </div>--}}
{{--        @endif--}}
{{--    </div>--}}
{{--    </div>--}}

{{--    <div class="form-group">--}}
{{--        <label for="personId">Ответственный</label>--}}
{{--        <select name="personId" class="form-control {{ $errors->has('personId') ? 'error' : '' }}" id="personId">--}}
{{--            @foreach($persons as $person )--}}
{{--                <option value="{{ $person->id }}">--}}
{{--                    {{ $person->name }}--}}
{{--                    {{ $person->surname }}--}}
{{--                    {{ $person->patronymic }}--}}
{{--                </option>--}}
{{--            @endforeach--}}

{{--        </select>--}}
{{--        @if ($errors->has('personId'))--}}
{{--            <div class="error">--}}
{{--                {{ $errors->first('personId') }}--}}
{{--            </div>--}}
{{--        @endif--}}
{{--    </div>--}}

{{--    <button type="submit">отправить</button>--}}
{{--</form>--}}


</body>
</html>
