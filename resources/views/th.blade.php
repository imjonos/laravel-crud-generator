<th @if($order) class="pointer" @click="order('{{ $columnName }}')" @endif>
	<div class="d-flex flex-nowrap align-items-center">
		<div>
			{{ $title }}
		</div>
		<div v-if="orderColumn == '{{ $columnName }}'">
			<template v-if="orderDirection == 'asc'">
				<i class="fas fa-sort-up"></i>
			</template>
			<template v-else-if="orderDirection == 'desc'">
				<i class="fas fa-sort-down"></i>
			</template>
		</div>
	</div>
</th>
