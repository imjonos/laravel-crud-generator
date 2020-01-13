<div class="custom-control custom-radio mb-3">
  	<input type="radio" v-model="{{ $vModel }}" id="{{ $vModel }}" class="custom-control-input">
  	<label class="custom-control-label" for="{{ $vModel }}">
	  	{{ $label }}
	  	@if($required ?? false)
		<span class="text-danger">*</span>
		@endif
  	</label>
  	<div class="text-danger" v-if="errors.has('{{ $name }}')" v-html="errors.first('{{ $name }}')"></div>
</div>