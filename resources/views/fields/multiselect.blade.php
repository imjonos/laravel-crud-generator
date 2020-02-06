<div class="form-group">
	<label for="">
		{{ $label }}
		@if($required ?? false)
		<span class="text-danger">*</span>
		@endif
	</label>
	<multi-select v-model="{{ $vModel }}" name="{{ $name }}" :options="{{ $options }}" placeholder="{{ $placeholder }}" class="form-control"></multi-select>
	<div class="text-danger" v-if="errors.has('{{ $name }}')" v-html="errors.first('{{ $name }}')"></div>
</div>
