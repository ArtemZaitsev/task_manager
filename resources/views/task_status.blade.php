@php
    $color = match($status){
    1 => 'table-light',
    2 => 'table-success',
    3 => 'table-warning',
    4 => 'table-primary',
    5 => 'table-secondary',
    6 => 'table-danger',
    7 => 'table-info',
    default => '',
    };
@endphp
<td class="text-left align-middle {{ $color }}"
    @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
    {{ \App\Models\Task::ALL_STATUSES[$status] }}
</td>
