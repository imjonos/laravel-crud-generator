<template>
    <div>
        <vue-multiselect v-model="selected"
                         :allowEmpty="allowEmpty"
                         :multiple="multiple"
                         :name="name"
                         :options="data"
                         :placeholder="placeholder"
                         :preselect-first="false"
                         :preserve-search="true"
                         :show-labels="false"
                         :taggable="multiple"
                         deselectLabel="x"
                         label="name"
                         open-direction="bottom"
                         selectLabel=""
                         selectedLabel=""
                         tagPlaceholder=""
                         track-by="id"
                         @search-change="getData">
            <template slot="singleLabel" slot-scope="props">
                {{ getItemTitle(props.option) }}
            </template>
            <template slot="tag" slot-scope="props">
                <span class="multiselect__tag">
                    <span> {{ getItemTitle(props.option) }} </span>
                    <i aria-hidden="true" class="multiselect__tag-icon" tabindex="1"
                       @click="removeTag(props.option.id)"></i>
                </span>
            </template>
            <template slot="option" slot-scope="props">
                <div class="option__desc">
                   <span class="option__title">
                        {{ getItemTitle(props.option) }}
                   </span>
                </div>
            </template>
        </vue-multiselect>
    </div>
</template>
<script>
import VueMultiselect from 'vue-multiselect'

export default {
    name: "multi-select",
    components: {
        VueMultiselect
    },
    data() {
        return {
            selected: this.value,
            data: this.options
        }
    },
    props: {
        placeholder: {
            type: String,
            default: ""
        },
        name: {
            type: String,
            default: ""
        },
        searchBy: {
            type: String,
            default: "name"
        },
        multiple: {
            type: Boolean,
            default: true
        },
        value: {
            type: Array | Object,
            default: () => []
        },
        allowEmpty: {
            type: Boolean,
            default: true
        },
        options: {
            type: Array,
            default: () => [
                {id: '1', name: 'Option'},
            ]
        },
        labelAttribute: {
            type: String,
            default: "asciiname"
        },
        resourceUrl: {
            type: String,
            default: ""
        },
        useQuery: {
            type: Boolean,
            default: false
        }
    },
    mounted() {
        this.getData()
        if (this.value) {
            if (!this.multiple) {
                this.getItem(this.value);
            } else {
                this.selected = _.map(this.value, item => {
                    let el = JSON.parse(JSON.stringify(item));
                    return {
                        id: el.id,
                        name: this.getItemTitle(el)
                    }
                });
            }
        }
    },
    methods: {
        removeTag(id) {
            let tags = JSON.parse(JSON.stringify(this.selected));
            _.remove(tags, el => Number(el.id) === Number(id));
            this.selected = tags;
        },
        getItem(id = null) {
            if (this.resourceUrl && id) {
                let index = this.resourceUrl.indexOf('?');
                let url = (index > 0) ? this.resourceUrl.slice(0, index) : this.resourceUrl;
                axios.get(url + '/' + id).then(response => {
                    let item = response.data.data;
                    if (item.hasOwnProperty('id')) {
                        this.selected = {
                            id: item.id,
                            name: this.getTitle(item)
                        }
                    }
                }).catch(error => {
                    console.log(error);
                });
            }
        },
        getItemTitle(item) {
            return item[this.labelAttribute];
        },
        getTitle(option) {
            //For example
            return option.attributes[this.labelAttribute];
        },
        getData(searchQuery = '') {
            if (this.resourceUrl) {
                let searchParams = '';
                if (_.size(searchQuery) > 1) {
                    searchParams = '?';
                    if (this.resourceUrl.indexOf('?') > 0) searchParams = '&';
                    searchParams += 'filter[' + this.searchBy + ']=' + searchQuery;
                }

                axios.get(this.resourceUrl + searchParams).then(response => {
                    let options = [];
                    if (searchQuery && this.useQuery) {
                        let isFind = false;
                        _.forEach(response.data.data, (option) => {
                            if (searchQuery.toLowerCase() === this.getTitle(option).toLowerCase()) {
                                isFind = true;
                            }
                        });

                        if (!isFind) {
                            options.push({
                                id: '0',
                                name: searchQuery
                            });
                        }
                    }

                    _.forEach(response.data.data, item => {
                        if (!_.isEmpty(item)) {
                            options.push({
                                id: item.id,
                                name: this.getTitle(item)
                            });
                        }
                    });

                    this.data = options;

                }).catch(error => {
                    console.log(error);
                });
            }
        }

    },
    watch: {
        selected(val) { //Для v-model в родитель
            let result = null;
            if (!_.isEmpty(val)) {
                if (this.multiple) {
                    result = val;
                } else
                    result = val.id;
            }

            this.$emit('input', result);
        },
        value(val) {
            if (_.isEmpty(val)) {
                this.selected = val;
            }
        }
    }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
