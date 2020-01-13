<template>
	<div class="mb-3">
		<vue-dropzone ref="dropzone" id="dropzone" :options="defaultOptions" v-on:vdropzone-success="onUpload"></vue-dropzone>
	</div>
</template>
<script type="text/javascript">
	export default {
		name: 'FileUpload',
		data() {
			return {
				defaultOptions: Object.assign({
					url: '/upload',
		          	thumbnailWidth: 200,
		          	thumbnailHeight: 200,
		          	addRemoveLinks: true,
                    timeout: 86400000,
		          	headers: {
		          		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		          	}
	          	}, this.options)
			}
		},
		props: {
			media: {
				type: Array,
				default: () => {
					return []
				}
			},
			options: {
				type: Object,
				default: () => {
					return {}
				}
			},
			path: {
				type: Boolean,
				required: false,
				default: false
			}
		},
		mounted() {
      		if (_.size(this.media)) {
      			_.forEach(this.media, (item) => {
      				this.$refs.dropzone.manuallyAddFile({
      					size: item.size,
      					name: item.name,
      					type: item.mime_type
      				}, this.path ? item.path : this.getPath(item));
      			})
      		}
    	},
    	methods: {
    		onUpload(file, response) {
    			file.path = response.path
    		},
    		getPath(item) {
    			return '/storage/' + item.id + '/' + item.file_name
    		}
    	}
	}
</script>
