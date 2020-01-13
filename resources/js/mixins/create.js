export default {
	name: 'MixinsCreate',
	data() {
        return {
            form: {},
            loading: false,
            mediaCollections: {},
            link: null
        }
    },
    props: {
        data: {
            default: () => {
                return {}
            }
        }
    },
    methods: {
        store() {
            if (this.loading) {
                return;
            }
            this.errors.clear();
            this.getMedia();
            this.loading = true;
            axios.post(this.link, this.form)
            .then(response => {
                window.location.href = this.link;
                this.systemMessage('success',{
                    'title':this.trans('crud.actions.info'),
                    'text':this.trans('crud.actions.success.create')
                });
            })
            .catch(error => {
                this.systemMessage('error',{
                    'title':this.trans('crud.actions.warning'),
                    'text':this.trans('crud.actions.fail.create')
                });
                _.forEach(error.response.data.errors, (item, index) => {
                    this.errors.add({
                        field: index,
                        msg: _.head(item)
                    });
                })
            })
            .finally(() => {
                this.loading = false;
            });
        },
        getMedia() {
            _.forEach(this.$refs, (item, index) => {
                if (item.$refs.dropzone) {
                    this.$set(this.mediaCollections, index, item.$refs.dropzone.getAcceptedFiles());
                }
            });
            this.form = Object.assign({
                'mediaCollections': this.mediaCollections
            }, this.form);
        },
        
    }
}