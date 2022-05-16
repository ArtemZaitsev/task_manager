<div @if($request->has($filter_name) && !empty($request->get($filter_name)) ) class="filter-applied" @endif>
    <input type="text" name="{{$filter_name}}" id="{{$filter_name}}"
           @if($request->has($filter_name)) value="{{$request->get($filter_name)}}"@endif>
    <button type="submit" class="btn btn-info mt-2">&#128269;</button>


    <a href="{{ App\Utils\UrlUtils::clearFilterUrl($route_name, $filter_name, request()) }}">
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
