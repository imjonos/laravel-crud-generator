<div class="form-group">
	<label for="">
		{{ $label }}
		@if($required ?? false)
		<span class="text-danger">*</span>
		@endif
	</label>
    <vue-editor v-model="{{ $vModel }}"></vue-editor>
	<div class="text-danger" v-if="errors.has('{{ $name }}')" v-html="errors.first('{{ $name }}')"></div>
</div>
