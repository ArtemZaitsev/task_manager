@extends('layouts.app')
@section('content')

<div>
    @impersonating()
    <a class="btn btn-outline-info m-1" href="{{ route('impersonate.leave') }}">Выйти из-под
        пользователя</a>
    @endImpersonating


    <div class="position-absolute top-0 end-0">
        <div><b>{{ Illuminate\Support\Facades\Auth::user()->labelFull()}}</b></div>
    </div>
</div>

<div>
    <a href="{{ route(\App\Http\Controllers\Task\TaskController::ACTION_LIST) }}" class="btn
                        btn-outline-success m-1">
        Задачи
    </a>
    <a href="{{ route(\App\Http\Controllers\Component\ComponentController::ROUTE_NAME) }}" class="btn
                        btn-outline-success m-1">
        Компоненты
    </a>
    <a href="{{ route(\App\Http\Controllers\Sz\SzListController::ROUTE_NAME) }}" class="btn
                        btn-outline-success m-1">
        СЗ
    </a>
</div>

    @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success')}}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-warning">
            {{ Session::get('error')}}
        </div>
    @endif

    @yield('grid')
@endsection
