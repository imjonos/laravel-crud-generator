<div class="form-group">
	<label for="">
		{{ $label }}
		@if($required)
		<span class="text-danger">*</span>
		@endif
	</label>
	<input type="number" v-model="{{ $vModel }}" class="form-control" placeholder="{{ $placeholder }}">
	<div class="text-danger" v-if="errors.has('{{ $name }}')" v-html="errors.first('{{ $name }}')"></div>
</div>