@extends('layouts.app')
@section('content')

    @include('layouts.navbar')

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
