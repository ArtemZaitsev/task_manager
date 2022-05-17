<div @if($request->has($filter_name) && !empty($request->get($filter_name)) ) class="filter-applied" @endif>

    <select name="{{$filter_name}}[]" id="{{$filter_name}}" class="select2" multiple="multiple">
        <option value=""></option>
        @foreach ($filter_data as $value => $label)

            <option value="{{$value}}"
                    @if($request->has($filter_name) && in_array($value,$request->get($filter_name))) selected="selected"@endif>
                {{$label}}
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-outline-success mt-1">
        <svg width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
        </svg>
    </button>


    <a href="{{ App\Utils\UrlUtils::clearFilterUrl($route_name, $filter_name, request()) }}">
        <button type="button" class="btn btn-outline-danger mt-1" title="Удалить">
            <svg width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                <path fill-rule="evenodd"
                      d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
            </svg>
        </button>
    </a>
</div>
