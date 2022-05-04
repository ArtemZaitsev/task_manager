<?php

return [
    'task_status_1' => 'New',
    'task_status_2' => 'Done',
    'task_status_3' => 'Not done',
    'task_status_4' => 'In progress',
    'task_status_5' => 'Refuse',

    'task_add_success' => 'Задача успешно добавлена',
    'task_edit_success' => 'Задача успешно отредактирована',
    'task_refresh_success' => 'Задача успешно обновлена',
    'task_del_success' => 'Задача успешно удалена',

    'task_log_del_success' => 'Проблема успешно удалена',

    \App\Http\Controllers\Filters\DateFilter::MODE_TODAY => 'Сегодня',
    \App\Http\Controllers\Filters\DateFilter::MODE_RANGE => 'Диапазон',
];
