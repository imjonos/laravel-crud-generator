export default {
    name: 'MixinsIndex',
    data() {
        return {
            data: {
                data: [],
                current_page: 1,
                per_page: 10
            },
            form: {},
            orderColumn: 'id',
            orderDirection: 'asc',
            loading: false,
            selectedCheckboxes: [],
            allSelected: false,
            link: null,
            parameters: ''
        }
    },
    mounted() {
        this.form = this.selected;
        let urlParams = new URLSearchParams(window.location.search);
        if(urlParams.has('page')){
            let data = JSON.parse(JSON.stringify(this.data));
            data.current_page = parseInt(urlParams.get('page'));
            this.data = data;
        }
        if(urlParams.has('order_column')){
            this.orderColumn = urlParams.get('order_column');
        }
        if(urlParams.has('order_direction')){
            this.orderDirection = urlParams.get('order_direction');
        }
        this.getData();
    },
    props: {
        selected: {
            default: () => {
                return []
            }
        }
    },
    methods: {
        getData(page = this.data.current_page) {
            if (this.loading) {
                return;
            }
            this.loading = true;
            this.$store.commit('setLoading', true, {
                root: true
            });

            let dataParams = '';

            let allParams = Object.assign(
                {
                    page: page,
                    per_page: this.data.per_page,
                    order_column: this.orderColumn,
                    order_direction: this.orderDirection,
                },
                this.form
            );

            _.forEach(allParams, (value, key) => {
                if (value) {
                    if (dataParams) dataParams += '&';
                    dataParams += key + '=' + value;
                }
            });

            if (dataParams) {
                this.parameters = '?' + dataParams;
                window.history.pushState('', '', this.link + this.parameters);
            }

            let params = {};
            _.forEach(this.form, (value, key) => {
                let result = value;
                if (_.isBoolean(result)) result = Number(result);
                if (result) {
                    return params[key] = result;
                }
            });

            axios.get(this.link, {
                params: Object.assign(
                    {
                        page: page,
                        per_page: this.data.per_page,
                        order_column: this.orderColumn,
                        order_direction: this.orderDirection,
                    },
                    params
                )
            })
                .then(response => {
                    console.log('response.data.data', response.data.data);
                    this.data = response.data.data
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        order(column) {
            this.orderColumn = column;
            this.orderDirection = (this.orderDirection === 'asc') ? 'desc' : 'asc';
            this.getData();
        },
        destroy(id) {
            this.$bvModal.msgBoxConfirm(this.trans('crud.confirmation.message'),
                {
                    okTitle: this.trans('crud.confirmation.yes'),
                    cancelTitle: this.trans('crud.confirmation.cancel'),
                })
                .then(value => {
                    if (value) {
                        axios.delete(this.link + '/' + id)
                            .then(response => {
                                this.getData();
                                this.systemMessage('success', {
                                    'title': this.trans('crud.actions.info'),
                                    'text': this.trans('crud.actions.success.delete')
                                });
                            })
                            .catch(error => {
                                this.systemMessage('error', {
                                    'title': this.trans('crud.actions.warning'),
                                    'text': this.trans('crud.actions.fail.delete')
                                });
                            });
                    }
                })
                .catch(err => {

                });
        },
        selectAll() {
            if (this.allSelected) {
                this.selectedCheckboxes = [];
                this.selectedCheckboxes = _.map(this.data.data, 'id');
            } else {
                this.selectedCheckboxes = [];
            }
        },
        checkItem() {
            this.allSelected = _.size(this.data.data) === _.size(this.selected);
        },
        massDestroy() {
            this.$bvModal.msgBoxConfirm(this.trans('crud.confirmation.message'),
                {
                    okTitle: this.trans('crud.confirmation.yes'),
                    cancelTitle: this.trans('crud.confirmation.cancel'),
                })
                .then(value => {
                    if (value) {
                        axios.post(this.link + '/massdestroy', {
                            selected: this.selectedCheckboxes
                        })
                            .then(response => {
                                this.selectedCheckboxes = [];
                                this.allSelected = false;
                                this.getData();
                                this.systemMessage('success', {
                                    'title': this.trans('crud.actions.info'),
                                    'text': this.trans('crud.actions.success.delete')
                                });
                            })
                            .catch(error => {
                                this.systemMessage('error', {
                                    'title': this.trans('crud.actions.warning'),
                                    'text': this.trans('crud.actions.fail.delete')
                                });
                            })
                    }
                })
                .catch(err => {

                });
        },
        clearFilters() {
            this.form = {};
            this.getData(1);
        },
        toggleBoolean(item, name) {
            axios.put(this.link + '/' + item.id + '/toggleboolean', {
                column_name: name,
                value: _.toInteger(item[name])
            })
                .then(response => {
                    this.systemMessage('success', {
                        'title': this.trans('crud.actions.info'),
                        'text': this.trans('crud.actions.success.edit')
                    });
                })
                .catch(error => {
                    this.systemMessage('error', {
                        'title': this.trans('crud.actions.warning'),
                        'text': this.trans('crud.actions.fail.edit')
                    });
                });
        },
    }
}
