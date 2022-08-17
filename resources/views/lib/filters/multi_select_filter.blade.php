<select name="filters[{{ $filter->name() }}][]" multiple class="select2">
    @foreach($filter->getFilterData() as $value => $label)
        <option value="{{$value}}" @if(in_array($value, $filterData['value'])) selected @endif>{{$label}}</option>
    @endforeach
</select>
