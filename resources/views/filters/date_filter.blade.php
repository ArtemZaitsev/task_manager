
<select name="{{ $filter_name }}[mode]" id="{{ $filter_name }}_mode" >
    <option value="" ></option>
    @foreach ( \App\Http\Controllers\Filters\DateFilter::ALL_MODES as $mode)
        <option value="{{ $mode }}" @if($request->has($filter_name) &&  $request->get($filter_name)['mode'] == $mode) selected @endif>
            {{  __('messages.'.$mode) }}
        </option>
    @endforeach
</select>
<input type="date" name="{{ $filter_name }}[start]"
       @if($request->has($filter_name)) value="{{ $request->get($filter_name)['start'] }}" @endif>
<input type="date" name="{{ $filter_name }}[end]"
       @if($request->has($filter_name)) value="{{ $request->get($filter_name)['end'] }}" @endif>
