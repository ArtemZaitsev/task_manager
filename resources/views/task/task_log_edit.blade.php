<table class="table table-bordered table-hover mt-5" id="task-logs">
    <colgroup>
        <col  span="1" style="width: 10%;" >
        <col span="1" style="width: 10%;">
        <col span="1" style="width: 10%;">
        <col span="1" style="width: 30%;">
        <col span="1" style="width: 30%;">
        <col span="1" style="width: 10%;">
    </colgroup>
    <thead id="task-logs-thead" @if (count($logs) == 0) style="display: none;" @endif>
    <tr  style="background-color: #d1f4ff ; ">
        <th class="align-middle" style="text-align: center; border-color: rgb(222, 226, 230);">Статус решения</th>
        <th class="align-middle" style="text-align: center;border-color: rgb(222, 226, 230); ">Дата обновления план</th>
        <th class="align-middle" style="text-align: center;border-color: rgb(222, 226, 230);">Дата обновления факт</th>
        <th class="align-middle" style="text-align: center;border-color: rgb(222, 226, 230);">Что мешает</th>
        <th class="align-middle" style="text-align: center;border-color: rgb(222, 226, 230);">Что делаем</th>
        <th class="align-middle" style="text-align: center;border-color: rgb(222, 226, 230);">Действия</th>
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
                <textarea name="task_log[{{$log->id}}][trouble]" style="width: 100%;" rows="3" required>{{ old("task_log
                .{$log->id}
                .trouble",
                $log->trouble) }}</textarea>
                @if ($errors->has("task_log.{$log->id}.trouble"))
                    <div class="error">
                        {{ $errors->first("task_log.{$log->id}.trouble") }}
                    </div>
                @endif
            </td>
            <td>
                <textarea type="text" name="task_log[{{$log->id}}][what_to_do]" style="width: 100%;" rows="3">{{
                ($log->what_to_do)}}</textarea>
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

