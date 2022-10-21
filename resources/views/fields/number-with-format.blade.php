<div class="form-group">
	<label for="" class="form-label">
		{{ $label }}
		@if($required)
		<span class="text-danger">*</span>
		@endif
	</label>
    <number v-model="{{ $vModel }}" decimal-places="2" class="form-control dt-input dt-full-name"></number>
	<div class="text-danger" v-if="errors.has('{{ $name }}')" v-html="errors.first('{{ $name }}')"></div>
</div>
