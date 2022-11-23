<div class="form-group">
    <label for="" class="form-label">
        {{ $label }}
        @if($required ?? false)
            <span class="text-danger">*</span>
        @endif
    </label>
    <select v-model="{{ $vModel }}" class="form-control dt-input dt-full-name">
        @if(is_iterable($options))
            @foreach($options as $key => $value)
                <option value="{{ $value }}">
                    {{ $key }}
                </option>
            @endforeach
        @else
            {{$options}}
        @endif
    </select>
    <div class="text-danger" v-if="errors.has('{{ $name }}')" v-html="errors.first('{{ $name }}')"></div>
</div>
