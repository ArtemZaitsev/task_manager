<script>
    function addTaskLog() {
        var taskLogsTable = document.getElementById('task-logs-tbody');
        var rowTemplate = document.getElementById('task-log-template');
        var newRow = rowTemplate.cloneNode(true);

        var allInputs = newRow.querySelectorAll('input,select');
        allInputs.forEach((input) => {
            var rowsCount = taskLogsTable.children.length;
            var nextId = (rowsCount * -1) - 1;
            var nameAttr = input.getAttribute('name');
            var newName = nameAttr.replaceAll('__id__', nextId);
            input.setAttribute('name', newName);
        });

        taskLogsTable.appendChild(newRow);
    }

    function deleteTaskLog(delLink) {
        var currentRow = delLink.closest('tr');
        currentRow.remove();
    }
</script>

<table class="table table-bordered table-hover mt-5" id="task-logs">
    <thead>
    <tr style="background-color: #d1f4ff ;">
        <th>Статус решения</th>
        <th>Дата обновления план</th>
        <th>Дата обновления факт</th>
        <th>Что мешает</th>
        <th>Что делаем</th>
        <th>Действия</th>
    </tr>
    </thead>
    <tbody id="task-logs-tbody">

    @foreach($logs as $log)
        <tr data-tasklog-id="{{ $log->id }}">
            <td>
                <input type="hidden" name="task_log[{{$log->id}}][id]" value="{{$log->id}}">
                <select name="task_log[{{$log->id}}][status]">
                    @foreach ( \App\Models\TaskLog::ALL_STATUSES as $value => $label)
                        <option value="{{$value}}"
                                @if( $value == old("task_log.{$log->id}.status",$log->status) ) selected @endif >
                            {{$label}}</option>
                    @endforeach
                </select>
                @if ($errors->has("task_log.{$log->id}.status"))
                    <div class="error">
                        {{ $errors->first("task_log.{$log->id}.status") }}
                    </div>
                @endif
            </td>
            <td>
                <input type="date" name="task_log[{{$log->id}}][date_refresh_plan]"
                       value="{{ \App\Utils\DateUtils::dateToHtmlInput($log->date_refresh_plan)  }}">
                @if ($errors->has("task_log.{$log->id}.date_refresh_plan"))
                    <div class="error">
                        {{ $errors->first("task_log.{$log->id}.date_refresh_plan") }}
                    </div>
                @endif
            </td>
            <td>
                <input type="date" name="task_log[{{$log->id}}][date_refresh_fact]"
                       value="{{ \App\Utils\DateUtils::dateToHtmlInput($log->date_refresh_fact)  }}">
                @if ($errors->has("task_log.{$log->id}.date_refresh_fact"))
                    <div class="error">
                        {{ $errors->first("task_log.{$log->id}.date_refresh_fact") }}
                    </div>
                @endif
            </td>
            <td>
                <input type="text" name="task_log[{{$log->id}}][trouble]"
                       value="{{ old("task_log.{$log->id}.trouble", $log->trouble)  }} "
                       required>
                @if ($errors->has("task_log.{$log->id}.trouble"))
                    <div class="error">
                        {{ $errors->first("task_log.{$log->id}.trouble") }}
                    </div>
                @endif
            </td>
            <td><input type="text" name="task_log[{{$log->id}}][what_to_do]"
                       value="{{($log->what_to_do)}}">
                @if ($errors->has("task_log.{$log->id}.what_to_do"))
                    <div class="error">
                        {{ $errors->first("task_log.{$log->id}.what_to_do") }}
                    </div>
                @endif
            </td>
            <td>
                <a class="btn btn-danger" onclick="deleteTaskLog(this)"> Удалить </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

