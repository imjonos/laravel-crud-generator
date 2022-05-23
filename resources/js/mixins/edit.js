/**
 * Eugeny Nosenko 2022
 * https://toprogram.ru
 * info@toprogram.ru
 */


export default {
    name: 'MixinsEdit',
    data() {
        return {
            form: this.data,
            loading: false,
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
        update() {
            if (this.loading) {
                return;
            }
            this.errors.clear();
            this.loading = true;
            axios.put(this.link + '/' + this.data.id, this.form)
                .then(response => {
                    window.location.href = this.link;
                    this.systemMessage('success', {
                        'title': this.trans('crud.actions.info'),
                        'text': this.trans('crud.actions.success.edit')
                    });
                })
                .catch(error => {
                    this.systemMessage('error', {
                        'title': this.trans('crud.actions.warning'),
                        'text': this.trans('crud.actions.fail.info')
                    });
                    _.forEach(error.response.data.errors, (item, index) => {
                        this.errors.add({
                            field: index,
                            msg: _.head(item)
                        });
                    });
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    }
}
