<div class="form-group">
	<label for="">
		{{ $label }}
		@if($required ?? false)
		<span class="text-danger">*</span>
		@endif
	</label>
	<select v-model="{{ $vModel }}" class="form-control">
		{{ $options }}
	</select>
	<div class="text-danger" v-if="errors.has('{{ $name }}')" v-html="errors.first('{{ $name }}')"></div>
</div>