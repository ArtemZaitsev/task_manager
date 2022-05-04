@php
$color = match($type){
    1 => 'table-success',
    2 => 'table-danger',
    default => '',
};
@endphp

<td class="text-left align-middle {{ $color }}"
    @if ( count($task->logs) > 1 ) rowspan="{{ count($task->logs) }}" @endif>
    {{ \App\Models\Task::All_TYPE[$type] }}
</td>
