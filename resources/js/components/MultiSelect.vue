<template>
    <div>
        <vue-multiselect v-model="selected"
                         :placeholder="placeholder"
                         :preserve-search="true"
                         open-direction="bottom"
                         label="name"
                         track-by="name"
                         :options="data"
                         :name="name"
                         :show-labels="false"
                         :taggable="multiple"
                         deselectLabel="x"
                         selectLabel=""
                         selectedLabel=""
                         tagPlaceholder=""
                         :preselect-first="false"
                         :allowEmpty="false"
                         @search-change="getData"
                         :multiple="multiple">
            <template slot="singleLabel" slot-scope="props">
                {{ props.option.name }}
            </template>
            <template slot="tag" slot-scope="props">
                <span class="multiselect__tag">
                    <span> {{ props.option.name }} </span>
                    <i aria-hidden="true" @click="removeTag(props.option.id)" tabindex="1" class="multiselect__tag-icon"></i>
                </span>
            </template>
            <template slot="option" slot-scope="props">
                <div class="option__desc">
                   <span class="option__title">
                        {{ props.option.name }}
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
        multiple: {
            type: Boolean,
            default: true
        },
        value: {
            type: Array | Object,
            default: () => []
        },
        options: {
            type: Array,
            default: () => [
                {id: '1', name: 'Option'},
            ]
        },
        resourceUrl: {
            type: String,
            default: ""
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        removeTag(id){
            let tags = JSON.parse(JSON.stringify(this.selected));
            _.remove(tags, el => Number(el.id) === Number(id));
            this.selected = tags;
        },
        getData(searchQuery = '') {
            if(this.resourceUrl) {
                let searchParams = '';
                if (_.size(searchQuery) > 1) {
                    searchParams = '?filter[name]=' + searchQuery;
                }

                axios.get(this.resourceUrl + searchParams).then(response => {
                    this.data = _.map(response.data.data, item => {
                        if (!_.isEmpty(item)) {
                            return {
                                id: item.id,
                                name: item.attributes.name
                            }
                        }
                    });
                }).catch(error => {
                    console.log(error);
                });
            }
        }

    },
    watch: {
        selected(val) { //Для v-model в родитель
            if(this.multiple) this.$emit('input', val);
            else
                this.$emit('input', val.id);
        }
    }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
