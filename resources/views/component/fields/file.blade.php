<div class="form-group">
    <label for="base" @if($required)class="required"@endif>{{ $label }}</label>
    <input name="{{ $fieldName }}" class="form-control {{ $errors->has($fieldName) ? 'is-invalid' : '' }}"
           id="{{ $fieldName }}" type="file">
    @if ($errors->has($fieldName))
        <div class="invalid-feedback">
            {{ $errors->first($fieldName) }}
        </div>
    @endif
</div>
