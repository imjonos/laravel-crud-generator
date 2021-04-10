<div class="form-group">
	<label for="">
		{{ $label }}
		@if($required ?? false)
		<span class="text-danger">*</span>
		@endif
	</label>
	<multi-select v-model="{{ $vModel }}"
                  name="{{ $name }}"
                  @if(isset($options))
                    :options="{{ $options }}"
                  @endif
                  @if(isset($resourceUrl))
                    :resource-url="{{ $resourceUrl }}"
                  @endif
                  placeholder="{{ $placeholder }}">
    </multi-select>
	<div class="text-danger" v-if="errors.has('{{ $name }}')" v-html="errors.first('{{ $name }}')"></div>
</div>
