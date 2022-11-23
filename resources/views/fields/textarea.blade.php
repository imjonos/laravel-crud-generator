<div class="form-group">
	<label for="" class="form-label">
		{{ $label }}
		@if($required ?? false)
		<span class="text-danger">*</span>
		@endif
	</label>
	<textarea v-model="{{ $vModel }}" class="form-control dt-input dt-full-name"></textarea>
	<div class="text-danger" v-if="errors.has('{{ $name }}')" v-html="errors.first('{{ $name }}')"></div>
</div>
