<div class="form-group">
	<label for="" class="form-label">
		{{ $label }}
		@if($required ?? false)
		<span class="text-danger">*</span>
		@endif
	</label>
	{{-- <input type="date" v-model="{{ $vModel }}" class="form-control"> --}}
	<flat-pickr v-model="{{ $vModel }}" :config="{ dateFormat: '{{ $format??'Y-m-d H:i:S' }}', altFormat: '{{ $format??'Y-m-d H:i:S' }}', altInput: true, altInputClass: 'form-control', enableTime: true }"></flat-pickr>
	<div class="text-danger" v-if="errors.has('{{ $name }}')" v-html="errors.first('{{ $name }}')"></div>
</div>
