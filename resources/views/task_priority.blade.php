@php
    $color = match($priority){
    1 => 'table-danger',
    2 => 'table-warning',
    3 => 'table-success',
    default => '',
    };
@endphp

<td class="text-left align-middle {{ $color }}"
    @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
    <span>{{ \App\Models\Task::All_PRIORITY[$priority] }}</span>
</td>
