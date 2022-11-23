<div class="form-group">
    <label for="" class="form-label">
        {{ $label }}
        @if($required ?? false)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input type="text"
           v-model="{{ $vModel }}"
           @if($disabled ?? false)
               disabled
           @endif
           class="form-control dt-input dt-full-name"
           placeholder="{{ $placeholder }}">
    <div class="text-danger" v-if="errors.has('{{ $name }}')" v-html="errors.first('{{ $name }}')"></div>
</div>
