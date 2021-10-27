<?php
//echo '<pre>';
////print_r($person);
////echo $person[0]['id'];die;
////echo count($person);die;
//
//for ($i=0; $i = count($person); $i++){
//
//    echo $person[$i];
//    echo '</br>';
//    echo $person[$i]['name'];
//    echo '<hr>';
//
////    echo $person[$i]['id'];
//
//}
//die;
////echo $person[0]['attributes:protected']['id'];die;
//


use Illuminate\Support\Facades\DB;
//echo '<pre>';
//$name_person = DB::table('user')->where('name', 'John')->pluck('name');
//$name_person = $person->where('id', '2')->pluck('name');
//echo $name_person;die;
//print_r ($person);
//$name_person = $person->where('id','1')->pluck('name');
//$person->where('id',$task->person_id)->pluck('name');
//print_r($name_person );
//die;
?>


    <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Планёр</title>
    <link rel="stylesheet" href="style.css">
    <!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js">-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js">
</head>
<body>
<div class="wrapper">
    <div class="header">
        <a class="btn btn-danger" href="{{ route(\App\Http\Controllers\LoginController::LOGOUT_ACTION) }}">Выход</a>
    </div>
    <div class="main">
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        @endif

        <form method="GET">
            <table>
                <tr>
                    <th>Имя</th>
                    <td> <input type="text" name="task_name" id="task_name" @if($request->has('task_name')) value="{{$request->get('task_name')}}" @endif></td>
                </tr>
                <tr>
                    <th>Дата начала</th>
                    <td>
                        @include('filters.date_filter', ['filter_name' => 'start_date'])
                    </td>
                </tr>
                <tr>
                    <th>Дата окончания</th>
                    <td>
                        @include('filters.date_filter', ['filter_name' => 'end_date'])
                    </td>
                </tr>

            </table>

            <h4>Сортировка</h4>
            <select name="order_column" id="">
                <option value=""></option>
                @foreach( $orderFields as $value => $label )
                    <option value="{{ $value }}" >
                       {{ $label }}
                    </option>
                @endforeach
            </select>
            <br>
            <select name="order_direction" id="">
                <option value="ASC">По возрастанию</option>
                <option value="DESC">По убыванию</option>
            </select>
            <br>
            <button type="submit">submit</button>
            <a href="{{ route(\App\Http\Controllers\Task\TaskController::ACTION_LIST) }}" class="btn btn-info" >reset</a>
        </form>

        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
            <tr>
                <th scope="col" class="text-center ">Управление задачей</th>
                <th scope="col" class="text-center">Управление номером</th>
                <th scope="col" class="text-center">Номер</th>
                <th scope="col" class="text-center">Название задачи</th>
                <th scope="col" class="text-center">Дата начала план</th>
                <th scope="col" class="text-center">Дата окончания план</th>
                <th scope="col" class="text-center">Дата переноса</th>
                <th scope="col" class="text-center">Ответственный</th>
                <th scope="col" class="text-center">Статус выполнения</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-success">Создать</button>
                            <button type="button" class="btn btn-info">Просмотреть</button>
                            <a href="{{ route('task.edit',['id' => $task->id]) }}" class="btn btn-warning">
                                Редактировать
                            </a>
                            <a href="{{ route('task.del',['id' => $task->id]) }}"  class="btn btn-danger">
                                Удалить
                            </a>
                        </div>
                    </td>

                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-outline-primary">вверх</button>
                            <button type="button" class="btn btn-outline-secondary">вниз</button>
                        </div>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-outline-success">вправо</button>
                            <button type="button" class="btn btn btn-outline-warning">влево</button>
                        </div>
                    </td>
                    <td class="text-center align-middle text-danger font-weight-bold">1.</td>
                    <td class="text-left align-middle">{{ $task->name }}</td>
                    <td class="text-center align-middle">{{ \Carbon\Carbon::parse($task->start_date)->format('d.m.Y')}}</td>
                    {{--                    <td class="text-center align-middle">{{ \Carbon\Carbon::parse($task->start_date)}}</td>--}}
                    <td class="text-center align-middle">{{ \Carbon\Carbon::parse($task->end_date)->format('d.m.Y')}}</td>
                    <td class="text-center align-middle text-danger font-weight-bold">05.01.2001</td>


                    <!--                    --><?php
                    //                    $name_person = $person->where('id',$task->person_id)->pluck('name');
                    ////                    $name_person = DB::table('people')->where('id','1')->pluck('name');
                    //                    print_r($name_person);
                    //                    echo($name_person[0]);
                    ////                    $person->where('id',$task->person_id)->pluck('name');
                    //                    ?>
                    <td class="text-center align-middle ">
                        {{ $task->user->surname }}
                        {{ $task->user->name }}
                        {{ $task->user->patronymic }}
                    </td>
                    {{--                    <td class="text-center align-middle text-danger font-weight-bold">{{ $person->where('id',$task->person_id)->pluck('name') }}</td>--}}
                    {{--                    <td class="text-center align-middle text-danger font-weight-bold">{{ $task->person_id}}</td>--}}

                    <td class="text-center align-middle">{{ __('messages.task_status_'.$task->status) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
</body>
</html>
