<div class="form-group mt-2">
    <label for="base" @if($required)class="required"@endif>{{ $label }}</label>
    <input name="{{ $fieldName }}" class="form-control {{ $errors->has($fieldName) ? 'is-invalid' : '' }}"
           id="{{ $fieldName }}" type="date"
           value="{{ \App\Utils\DateUtils::dateToHtmlInput(old($fieldName, $currentValue))  }}">
    @if ($errors->has($fieldName))
        <div class="invalid-feedback">
            {{ $errors->first($fieldName) }}
        </div>
    @endif
</div>
