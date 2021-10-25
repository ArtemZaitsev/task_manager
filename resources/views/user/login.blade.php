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
<form method="post" action="{{ route('login') }}">
    @csrf
    <div class="form-group">
        <label for="email">Email</label>
        <input  name="email" class="form-control {{ $errors->has('email') ? 'error' : '' }}"
                id="email" type="text" value="{{ old('email')  }}"  >
        @if ($errors->has('email'))
            <div class="error">
                {{ $errors->first('email') }}
            </div>
        @endif
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input  name="password" class="form-control {{ $errors->has('password') ? 'error' : '' }}"
                id="password" type="password" value="{{ old('password')  }}"  >
        @if ($errors->has('password'))
            <div class="error">
                {{ $errors->first('password') }}
            </div>
        @endif
    </div>

    <button type="submit">отправить</button>
</form>
</body>
</html>
