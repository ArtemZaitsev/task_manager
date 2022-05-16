<div data-filter="date" @if($request->has($filter_name) && \App\Http\Controllers\Filters\DateFilter::filterEnabled
($request->get($filter_name))) class="filter-applied" @endif >
    <select data-type="mode" name="{{ $filter_name }}[mode]" id="{{ $filter_name }}_mode">
        @foreach ( \App\Http\Controllers\Filters\DateFilter::ALL_MODES as $mode)
            <option value="{{ $mode }}" @if(($request->has($filter_name)  &&  $request->get($filter_name)['mode'] ==
        $mode ) || (!($request->has($filter_name)) && $mode === "range")) selected @endif>
                {{  __('messages.'.$mode) }}
            </option>
        @endforeach
    </select>
    <input data-type="start" type="date" name="{{ $filter_name }}[start]"
           @if($request->has($filter_name) && isset($request->get($filter_name)['start'])) value="{{ $request->get($filter_name)['start'] }}" @endif>
    <input data-type="end" type="date" name="{{ $filter_name }}[end]"
           @if($request->has($filter_name) && isset($request->get($filter_name)['end'])) value="{{ $request->get($filter_name)['end'] }}" @endif>


    <br>
    <button type="submit" class="btn btn-info mt-2">
        &#128269;
    </button>

    <a href="{{ App\Utils\UrlUtils::clearFilterUrl($route_name,  $filter_name, request()) }}">
        <button type="button" class="btn btn-outline-danger" title="Удалить">
            <svg width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                <path fill-rule="evenodd"
                      d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
            </svg>
        </button>
    </a>
</div>

