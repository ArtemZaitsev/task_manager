<div data-filter="date">
    <select data-type="mode" name="filters[{{ $filter->name() }}][mode]">
        @foreach ( \App\Http\Controllers\Filters\DateFilter::ALL_MODES as $mode)
            <option value="{{ $mode }}" @if($mode == $filterData['mode']) selected @endif>
                {{  __('messages.'.$mode) }}
            </option>
        @endforeach
    </select>
    <input data-type="start" type="date" name="filters[{{  $filter->name() }}][start]"
           value="{{ $filterData['start'] ?? '' }}">
    <input data-type="end" type="date" name="filters[{{  $filter->name() }}][end]"
           value="{{$filterData['end'] ?? ''}}">

</div>
