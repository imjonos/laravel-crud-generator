<template>
    <div>
        <button class="btn btn-primary"
                data-target="#modal"
                data-toggle="modal"
                type="button"
                @click="showModal()">
            <span class="fa fa-list"></span> {{ trans('crud.buttons.create') }}
        </button>
        <div id="modal" aria-hidden="true" aria-labelledby="mySmallModalLabel"
             class="modal fade bd-example-modal-sm"
             role="dialog" tabindex="-1">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ title }}
                        </h5>
                        <button aria-label="Close" class="btn btn-light" type="button"
                                @click="closeModal()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <slot></slot>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" @click="closeModal()">
                            {{ trans('crud.buttons.close') }}
                        </button>
                        <button class="btn btn-primary" type="button" @click="onSave()">
                            {{
                                trans('crud.buttons.save')
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>


export default {
    name: "ModalDialog",
    data() {
        return {};
    },
    props: {
        title: {
            type: String,
            default: 'Modal'
        },
        modalId: {
            type: String,
            default: 'modal'
        }
    },
    methods: {
        onSave() {
            this.$emit('save');
            this.closeModal();
        },
        showModal() {
            $('#' + this.modalId).modal('show');
        },
        closeModal() {
            $('#' + this.modalId).modal('hide');
        }
    }
}
</script>

<style scoped>

</style>
