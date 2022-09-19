<div class="form-group">
    <label for="{{ $fieldName }}">{{ $label }}</label>
    <select name="{{ $fieldName }}@if($multiple)[]@endif"
            class="select2 form-control {{ $errors->has($fieldName) ? 'is-invalid' : '' }}"
            id="{{$fieldName}}" @if($multiple)multiple="multiple"@endif
    @if(isset($attrs))
        @foreach($attrs as $key => $value){{$key}}="{{$value}}"@endforeach
    @endif
    >

    @if($required === false)
        <option value=""></option>
    @endif
    @foreach($data as $value => $label )
        <option value="{{ $value }}"
                @if($multiple)
                @if(in_array($value, old($fieldName,$currentValue))) selected @endif
                @else
                @if($value == old($fieldName,$currentValue)) selected @endif
            @endif>
            {{ $label }}
        </option>
        @endforeach
        </select>
        @if ($errors->has($fieldName))
            <div class="invalid-feedback">
                {{ $errors->first($fieldName) }}
            </div>
        @endif
</div>
