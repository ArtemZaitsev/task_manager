<div @if($request->has($filter_name) && !empty($request->get($filter_name)) ) class="filter-applied" @endif>
    <input type="text" name="{{$filter_name}}" id="{{$filter_name}}"
           @if($request->has($filter_name)) value="{{$request->get($filter_name)}}"@endif>
    <button type="submit" class="btn btn-info mt-2">&#128269;</button>
    <a href="{{ App\Utils\UrlUtils::clearFilterUrl($route_name, $filter_name, request()) }}"
       class="btn btn-info mt-2">&#10060;</a>
</div>
