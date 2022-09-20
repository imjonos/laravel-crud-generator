<div class="form-group">
    <label for="">
        {{ $label }}
        @if($required ?? false)
            <span class="text-danger">*</span>
        @endif
    </label>
    <multi-select
        v-model="{{ $vModel }}"
        name="{{ $name }}"
        @if(isset($options))
            :options="{{ $options }}"
        @endif
        @if(isset($ref))
            ref="{{ $ref }}"
        @endif
        @if(isset($labelAttribute))
            label-attribute="{{ $labelAttribute }}"
        @endif
        @if(isset($resourceUrl))
            :resource-url="@if(!str_contains($resourceUrl, '?')) '{{ str_replace('\'', '', $resourceUrl) }}'{{ (isset($urlParams) && is_array($urlParams)?'+\'?'.http_build_query($urlParams).'\'':'') }} @else {{$resourceUrl}} @endif"
        @endif
        @if(isset($useQuery))
            :use-query="{{ $useQuery }}"
        @endif
        :multiple="{{ (!isset($multiple) || (bool) $multiple === 1)? 'true' : 'false' }}"
        :allow-empty="{{ (!isset($required) || (bool) $required === 0)? 'true' : 'false' }}"
        placeholder="{{ $placeholder }}">
    </multi-select>
    <div class="text-danger" v-if="errors.has('{{ $name }}')" v-html="errors.first('{{ $name }}')"></div>
</div>
