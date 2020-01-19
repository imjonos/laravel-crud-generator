<div class="form-group">
	<label for="">
		{{ $label }}
		@if($required ?? false)
		<span class="text-danger">*</span>
		@endif
	</label>
    <file-upload v-model="{{ $vModel }}" name="{{ $name }}" placeholder="{{ $placeholder }}"></file-upload>
	<div class="text-danger" v-if="errors.has('{{ $name }}')" v-html="errors.first('{{ $name }}')"></div>
</div>
