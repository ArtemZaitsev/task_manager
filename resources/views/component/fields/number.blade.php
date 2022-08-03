<div class="form-group">
    <label for="base" @if($required)class="required"@endif>{{ $label }}</label>
    <input
        name="{{ $fieldName }}"
        class="form-control {{ $errors->has($fieldName) ? 'is-invalid' : '' }}"
        id="{{ $fieldName }}"
        type="number"
        step="1"
        min="0"
        value="{{ old($fieldName, $currentValue)  }}">
    @if ($errors->has($fieldName))
        <div class="invalid-feedback">
            {{ $errors->first($fieldName) }}
        </div>
    @endif
</div>
