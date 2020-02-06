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
                         :taggable="true"
                         deselectLabel="x"
                         selectLabel=""
                         selectedLabel=""
                         tagPlaceholder=""
                         :preselect-first="false"
                         :allowEmpty="false"
                         :multiple="true">
            <template slot="singleLabel" slot-scope="props">
                {{ props.option.name }}
            </template>
            <template slot="tag" slot-scope="props">
                <span class="multiselect__tag">
                    <span> {{ props.option.name }} </span>
                    <i aria-hidden="true" tabindex="1" class="multiselect__tag-icon"></i>
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
                default: ""
            },
            name: {
                default: ""
            },
            value: {
                default: () => []
            },
            options: {
                default: () => [
                    {id: '1', name: 'Option'},
                ]
            },
        },
        methods: {},
        watch: {
            selected(val) { //Для v-model в родитель
                this.$emit('input', val);
            }
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
