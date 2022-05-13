<div @if($request->has($filter_name) && !empty($request->get($filter_name)) ) class="filter-applied" @endif>

    <select name="{{$filter_name}}[]" id="{{$filter_name}}" class="select2" multiple="multiple">
        <option value=""></option>
        @foreach ($filter_data as $value)

            <option value="{{$value->id}}"
                    @if($request->has($filter_name) && in_array($value->id, $request->get($filter_name))) selected="selected"@endif>
                {{ $value->label() }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-info mt-2">&#128269;</button>
    <a href="{{ App\Utils\UrlUtils::clearFilterUrl($route_name, $filter_name, request()) }}"
       class="btn btn-info mt-2">
        &#10060;
    </a>
</div>
