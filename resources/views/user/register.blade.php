<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/admin.css">
    {{--    <link rel="stylesheet" href="../../../public/css/all.min.css">--}}
    <style>
        /*body {*/
        /*    background: url("img/background.jpg");*/
        /*}*/

        .fullscreen-bg__video {
            position: absolute;
            top: 0;
            left: 0;
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

    </style>

    <title>Регистрация</title>

</head>
<body class="hold-transition register-page">

<video autoplay loop muted class="fullscreen-bg__video">
    {{--<video autoplay loop muted class="bgvideo" id="bgvideo">--}}
    <source src="video/ocean.mp4" type="video/mp4">
</video>


<div class="card">
    <div class="card-body">
    <form action="{{ route(\App\Http\Controllers\RegisterController::REGISTER_ACTION) }}" method="POST">
    @csrf

    <div class="input-group-append mt-3">
        <input name="surname" type="text" class="form-control {{ $errors->has('surname') ? 'error' : '' }}"  id="surname"
               placeholder="Введите свою фамилию" value="{{ old('surname')  }}"  required>
        @if ($errors->has('surname'))
            <div class="error text-danger">
                {{ $errors->first('surname') }}
            </div>
        @endif
    </div>

    <div class="input-group-append mt-3">
        <input name="name" type="text" class="form-control" id="name"
               placeholder="Введите свое имя" value="{{ old('name') }}" required>
        @if ($errors->has('name'))
            <div class="small text-danger">
                {{ $errors->first('name') }}
            </div>
        @endif
    </div>

    <div class="input-group-append mt-3">
        <input name="patronymic" type="text" class="form-control" id="patronymic"
               placeholder="Введите свое отчество"  value="{{ old('patronymic') }}" >
        @if ($errors->has('patronymic'))
            <div class="error text-danger">
                {{ $errors->first('patronymic') }}
            </div>
        @endif
    </div>

        <div class="input-group-append mt-3">
            <input name="email" type="email" class="form-control {{ $errors->has('email') ? 'error' : '' }}"
                   placeholder="Электронная почта" value="{{ old('email')  }} ">

        </div>
        @error('email')
        <div class="small text-danger">
            {{ $message}}
        </div>
        @enderror
        <div class="input-group-append mt-3">
            <input name="password" type="password"
                   class="form-control {{ $errors->has('password') ? 'error' : '' }}"
                   placeholder="Пароль" value="{{ old('password')  }}">
        </div>
        @if ($errors->has('password'))
            <div class="small text-danger">
                {{ $errors->first('password') }}
            </div>
        @endif
        <div class="input-group-append mt-3">
            <input name="password_confirmation" type="password"
                   class="form-control {{ $errors->has('password_confirmation') ? 'error' : '' }}"
                   placeholder="Повторите пароль" value="{{ old('password_confirmation')  }}">
        </div>
        @if ($errors->has('password_confirmation'))
            <div class="small text-danger">
                {{ $errors->first('password_confirmation') }}
            </div>
        @endif
    {{--    <div class="form-check">--}}
    {{--        <input type="checkbox" class="form-check-input" id="exampleCheck1">--}}
    {{--        <label class="form-check-label" for="exampleCheck1">Check me out</label>--}}
    {{--    </div>--}}

        <div class="row">

            <!-- /.col -->
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary btn-block">Зарегистрироваться</button>
            </div>
            <!-- /.col -->
        </div>
</form>




</body>
</html>


