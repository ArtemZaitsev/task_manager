<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>

<form action="{{ route(\App\Http\Controllers\RegisterController::AUTHENTICATE_ACTION) }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="email">Email address</label>
        <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp"
               placeholder="Enter email" required>
        @if ($errors->has('email'))
            <div class="error">
                {{ $errors->first('email') }}
            </div>
        @endif
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input name="password" type="password" class="form-control" id="password" placeholder="Password" required>
        @if ($errors->has('password'))
            <div class="error">
                {{ $errors->first('password') }}
            </div>
        @endif
    </div>
{{--    <div class="form-check">--}}
{{--        <input type="checkbox" class="form-check-input" id="exampleCheck1">--}}
{{--        <label class="form-check-label" for="exampleCheck1">Check me out</label>--}}
{{--    </div>--}}
    <button type="submit" class="btn btn-primary">Submit</button>
</form>




</body>
</html>
