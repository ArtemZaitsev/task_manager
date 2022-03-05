<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Авторизация</title>


    <!-- Google Font: Source Sans Pro -->
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

<body class="hold-transition register-page">


<video autoplay loop muted class="fullscreen-bg__video">
    {{--<video autoplay loop muted class="bgvideo" id="bgvideo">--}}
    <source src="video/ocean.mp4" type="video/mp4">
</video>

<div class="register-box">


    <div class="card">
        {{--        <div class="register-logo">--}}
        {{--            <b>Авторизация</b>--}}
        {{--        </div>--}}

        {{--        <div class="card-body register-card-body ">--}}
        <div class="card-body">
{{--            @if ($errors->any())--}}
{{--                <div class="alert alert-danger">--}}

{{--                    <ul>--}}
{{--                        @foreach ($errors->all() as $error)--}}
{{--                            <li>{{ $error }}</li>--}}
{{--                        @endforeach--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--            @if(session()->has('error'))--}}
{{--                <div class="alert alert-danger">--}}
{{--                    {{ session('error') }}--}}
{{--                </div>--}}
{{--            @endif--}}

            <form action="{{ route(\App\Http\Controllers\LoginController::AUTHENTICATE_ACTION) }}" method="post">
                @csrf

                <div class="input-group-append mt-3">
                    <input name="email" type="email" class="form-control {{ $errors->has('email') ? 'error' : '' }}"
                           placeholder="Электронная почта" value="{{ old('email')  }} ">

{{--                                        <div class="input-group-append">--}}
{{--                                            <div class="input-group-text">--}}
{{--                                                <span class="fas fa-envelope"></span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

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

                    {{--                    <div class="input-group-append">--}}
                    {{--                        <div class="input-group-text">--}}
                    {{--                            <span class="fas fa-lock"></span>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                </div>
                @if ($errors->has('password'))
                    <div class="small text-danger">
                        {{ $errors->first('password') }}
                    </div>
                @endif

                <div class="row">

                    <!-- /.col -->
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary btn-block">Войти</button>
                    </div>


                    <!-- /.col -->
                </div>
            </form>

            {{--                <br>--}}
            {{--                    <a href="#" class="text-center mt-3">Зарегистрироваться</a>--}}
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>
<!-- /.register-box -->

<script src="{{ asset('public/assets/admin/js/admin.js') }}"></script>
</body>
</html>
