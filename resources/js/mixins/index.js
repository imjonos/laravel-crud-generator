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
            selected_checkboxes: [],
            allSelected: false,
            link: null,
            parameters: ''
        }
    },
    mounted() {
        this.form = this.selected;
        this.getData()
    },
    props: {
        selected : {
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
            this.parameters = '?';
            _.forEach(this.form, (value, key) => {
                if (value) {
                    this.parameters += key + '=' + value + '&';
                }
            });
            window.history.pushState('', '', this.link + this.parameters);
            axios.get(this.link, {
                params: Object.assign(
                    {
                        page: page,
                        per_page: this.data.per_page,
                        order_column: this.orderColumn,
                        order_direction: this.orderDirection,
                    },
                    _.forEach(this.form, (value, key) => {
                        if (!value) this.$delete(this.form, key)
                    })
                )
            })
            .then(response => {
                this.data = response.data.data
            })
            .finally(() => {
                this.loading = false;
            });
        },
        order(column) {
            this.orderColumn = column;
            this.orderDirection = this.orderDirection == 'asc' ? 'desc' : 'asc';
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
                        this.systemMessage('success',{
                            'title':this.trans('crud.actions.info'),
                            'text':this.trans('crud.actions.success.delete')
                        });
                    })
                    .catch(error => {
                        this.systemMessage('error',{
                            'title':this.trans('crud.actions.warning'),
                            'text':this.trans('crud.actions.fail.delete')
                        });
                    });
                }
            })
            .catch(err => {

            });
        },
        selectAll() {
            if (this.allSelected) {
                this.selected_checkboxes = [];
                this.selected_checkboxes = _.map(this.data.data, 'id');
            } else {
                this.selected_checkboxes = [];
            }
        },
        checkItem() {
            if (_.size(this.data.data) == _.size(this.selected)) {
                this.allSelected = true;
            } else {
                this.allSelected = false;
            }
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
                        selected: this.selected
                    })
                    .then(response => {
                        this.selected = [];
                        this.allSelected = false;
                        this.getData()
                        this.systemMessage('success',{
                               'title':this.trans('crud.actions.info'),
                               'text':this.trans('crud.actions.success.delete')
                        });
                    })
                    .catch(error => {
                         this.systemMessage('error',{
                                'title':this.trans('crud.actions.warning'),
                                'text':this.trans('crud.actions.fail.delete')
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
                this.systemMessage('success',{
                     'title':this.trans('crud.actions.info'),
                     'text':this.trans('crud.actions.success.edit')
                });
            })
            .catch(error => {
                 this.systemMessage('error',{
                     'title':this.trans('crud.actions.warning'),
                     'text':this.trans('crud.actions.fail.edit')
                 });
            });
        },
    }
}
