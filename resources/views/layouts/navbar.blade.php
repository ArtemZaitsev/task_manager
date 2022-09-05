<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
{{--        <a class="navbar-brand" href="/">Цуп</a>--}}

{{--        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"--}}
{{--                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"--}}
{{--                aria-label="Toggle navigation">--}}
{{--            <span class="navbar-toggler-icon"></span>--}}
{{--        </button>--}}
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                @php($menuItems = [
                ['label' => 'Задачи', 'route' => \App\Http\Controllers\Task\TaskController::ACTION_LIST],
                ['label'=> 'Компоненты', 'route' => \App\Http\Controllers\Component\ComponentController::ROUTE_NAME],
                ['label'=> 'СЗ на изготовление', 'route' => \App\Http\Controllers\Sz\SzListController::ROUTE_NAME],
                ['label'=> 'Заявки на закупки', 'route' =>
                \App\Http\Controllers\PurchaseOrder\PurchaseOrderListController::ROUTE_NAME],
                ['label'=> 'ТЗ на расчет', 'route' => \App\Http\Controllers\TechnicalTaskCalculation\TechnicalTaskCalculationListController::ROUTE_NAME],

                ])

                @foreach($menuItems as $menuItem)
                    <li class="nav-item">
                        <a class="nav-link
@if($menuItem['route'] == request()->route()->getName()) active @endif"
                           href="{{route($menuItem['route'])
                        }}">
                            {{$menuItem['label']}}
                        </a>
                    </li>
                @endforeach

                <li class="nav-item position-absolute end-0">

                    <a class="btn btn-sm btn-outline-secondary m-1 "
                       href="{{ route(\App\Http\Controllers\LoginController::LOGOUT_ACTION) }}">
                        Выход из системы
                    </a>

                    @impersonating()
                    <a class="btn btn-sm btn-outline-info m-1" href="{{ route('impersonate.leave') }}">Выйти из-под
                        пользователя</a>
                    @endImpersonating

                        <b>{{ Illuminate\Support\Facades\Auth::user()->labelFull()}}</b>

                </li>
            </ul>
        </div>
    </div>
</nav>
