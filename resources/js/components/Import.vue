<template>
    <span>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importModal" :disabled="buttonDisabled">
            <i class="fas fa-file-import"></i>
            {{ trans('crud.labels.import') }}
        </button>
        <!-- Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
             aria-hidden="true" ref="import-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">{{ trans('crud.labels.import') }}</h5>
                        <button type="button" class="close" data-dismiss="modal"
                                :aria-label="trans('crud.buttons.close')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" id="file" ref="file" v-on:change="handleFileUpload()">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ trans('crud.buttons.close') }}
                        </button>
                        <button type="submit" v-on:click="submitFile()" class="btn btn-primary">
                            {{ trans('crud.buttons.send') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </span>
</template>

<script>
    export default {
        name: 'Import',
        /*
          Defines the data used by the component
        */
        data() {
            return {
                file: '',
                buttonDisabled: false
            }
        },
        props: {
            route: {
                type: String
            },
        },

        methods: {
            /*
              Submits the file to the server
            */
            submitFile() {

                if(this.buttonDisabled) return;

                this.buttonDisabled = true;
                /*
                   Initialize the form data
                */
                let formData = new FormData();

                /*
                    Add the form data we need to submit
                */
                formData.append('import_file', this.file);

                /*
                  Make the request to the POST /single-file URL
                */
                axios.post(this.route,
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then((response) => {
                    this.systemMessage("success",{
                        title: this.trans("crud.actions.info"),
                        text: this.trans("crud.actions.success.import")
                    });
                    window.location.reload();
                }).catch((error) => {
                    console.log(error.response.data.message);
                    this.systemMessage("error",{
                        title: this.trans("crud.actions.fail.import"),
                        text: error.response.data.errors.import_file[0]
                    });
                    this.buttonDisabled = false;
                });
            },

            /*
              Handles a change on the file upload
            */
            handleFileUpload() {
                this.file = this.$refs.file.files[0];
            }
        }
    }
</script>
