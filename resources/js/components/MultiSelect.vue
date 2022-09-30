<template>
    <div>
        <vue-multiselect :ref="name"
                         v-model="selected"
                         :allowEmpty="allowEmpty"
                         :label="labelAttribute"
                         :multiple="multiple"
                         :name="name"
                         :options="data"
                         :placeholder="placeholder"
                         :preselect-first="false"
                         :preserve-search="true"
                         :selectLabel="selectLabel"
                         :show-labels="false"
                         :tagPlaceholder="placeholder"
                         :taggable="multiple"
                         deselectLabel="x"
                         open-direction="bottom"
                         selectedLabel=""
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
            data: this.options,
            url: this.resourceUrl
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
            default: 'name'
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
            default: 'name'
        },
        resourceUrl: {
            type: String,
            default: ''
        },
        selectLabel: {
            type: String,
            default: 'Select'
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
                this.selected = JSON.parse(JSON.stringify(this.value));
            }
        }
    },
    methods: {
        setResourceUrl(url) {
            this.url = url;
        },
        setSearch(search) {
            this.$refs[this.name].search = search;
        },
        removeTag(id) {
            let tags = JSON.parse(JSON.stringify(this.selected));
            _.remove(tags, el => String(el.id) === String(id));
            this.selected = tags;
        },
        getItem(id = null) {
            if (this.url && id) {
                let index = this.url.indexOf('?');
                let url = (index > 0) ? this.url.slice(0, index) : this.url;
                axios.get(url + '/' + id).then(response => {
                    let item = response.data.data;
                    if (item.hasOwnProperty('id')) {
                        this.selected = this.getOption(item);
                    }
                }).catch(error => {
                    console.log(error);
                });
            }
        },
        getItemTitle(item) {
            return item[this.labelAttribute] ?? this.placeholder;
        },
        getTitle(option) {
            //For example
            return option.attributes[this.labelAttribute];
        },
        getOption(option) {
            let result = {
                id: option.id
            };
            result[this.labelAttribute] = this.getTitle(option);

            return result;
        },
        getDataSource(response) {
            return response.data.data;
        },
        getSearchParam(searchQuery) {
            return 'filter[' + this.searchBy + ']=' + searchQuery;
        },
        getData(searchQuery = '') {
            if (this.url) {
                let searchParams = '';
                if (_.size(searchQuery) > 1) {
                    searchParams = '?';
                    if (this.url.indexOf('?') > 0) searchParams = '&';
                    searchParams += this.getSearchParam(searchQuery);
                }

                axios.get(this.url + searchParams).then(response => {
                    let options = [];
                    if (searchQuery && this.useQuery) {
                        let isFind = false;
                        _.forEach(response.data.data, (option) => {
                            if (searchQuery.toLowerCase() === this.getTitle(option).toLowerCase()) {
                                isFind = true;
                            }
                        });

                        if (!isFind) {
                            let itemData = {
                                id: '0',
                            };
                            itemData[this.labelAttribute] = searchQuery;
                            options.push(itemData);
                        }
                    }

                    _.forEach(this.getDataSource(response), item => {
                        if (!_.isEmpty(item)) {
                            let option = this.getOption(item);
                            options.push(option);
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
        value(newVal, oldVal) {
            if ((!_.isEmpty(newVal) || newVal !== 0) && oldVal !== newVal) {
                console.log(oldVal, newVal);
                this.getItem(newVal);
            } else if (newVal === 0) {
                this.selected = null;
            }
        }
    }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
