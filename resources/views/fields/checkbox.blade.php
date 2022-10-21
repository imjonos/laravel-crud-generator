<div class="custom-control custom-checkbox mb-1 mt-2">
    <input type="checkbox" v-model="{{ $vModel }}" class="custom-control-input" id="{{ $vModel }}"
           @if($checked ?? false) checked @endif>
    <label class="custom-control-label" for="{{ $vModel }}">
        {{ $label }}
        @if($required ?? false)
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="text-danger" v-if="errors.has('{{ $name }}')" v-html="errors.first('{{ $name }}')"></div>
</div>
