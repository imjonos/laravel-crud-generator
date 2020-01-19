<template>
	<div class="mb-3">
		<vue-dropzone ref="dropzone"
                      id="dropzone"
                      :options="defaultOptions"
                      v-on:vdropzone-success="onUpload"
                      :useCustomSlot=true
        >
            <div class="dropzone-custom-content">
                <h6>{{ placeholder }}</h6>
            </div>
        </vue-dropzone>
	</div>
</template>
<script type="text/javascript">
	export default {
		name: 'FileUpload',
		data() {
			return {
			    files: this.value,
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
		    name: {
                type: String,
                default: "MediaCollection"
            },
		    placeholder: {
		       type: String,
		       default: "Drag and drop to upload file"
            },
			value: {
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
      		if (_.size(this.value)) {
      			_.forEach(this.value, (item) => {
      				this.$refs.dropzone.manuallyAddFile({
      					size: item.size,
      					name: item.name,
      					type: item.mime_type
      				}, this.path ? item.path : this.getPath(item));
      			})
      		}
    	},
    	methods: {
		    getFiles(){
                this.files = {
                    "name": this.name,
                    "files": this.$refs.dropzone.getAcceptedFiles()
                };
                this.$emit('input', this.files);
            },
    		onUpload(file, response) {
    			file.path = response.path;
    			this.getFiles();
    		},
    		getPath(item) {
    			return '/storage/' + item.id + '/' + item.file_name;
    		}
    	}
	}
</script>
