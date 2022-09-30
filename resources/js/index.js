import BootstrapVue from 'bootstrap-vue'
import lodash from 'lodash';
import '@fortawesome/fontawesome-free/js/fontawesome';
import Notifications from 'vue-notification';
import VeeValidate from 'vee-validate';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';
import {VueEditor} from "vue2-editor/dist/vue2-editor.common";
import store from "./store/index";
import MixinsTrans from './mixins/trans'

Object.defineProperty(Vue.prototype, '_', {value: lodash});
Vue.component('file-upload', require('./components/FileUpload.vue').default);
Vue.component('import', require('./components/Import.vue').default);
Vue.component('multi-select', require('./components/MultiSelect.vue').default);
Vue.component('vue-dropzone', vue2Dropzone);
require('./index');
Vue.use(Notifications);
Vue.use(BootstrapVue);
Vue.use(VeeValidate);
Vue.use(flatPickr);
VueEditor.methods.emitImageInfo = function emitImageInfo($event) {
    let resetUploader = function resetUploader() {
        let uploader = document.getElementById("file-upload");
        uploader.value = "";
    };

    let file = $event.target.files[0];
    let Editor = this.quill;
    let range = Editor.getSelection();
    let cursorLocation = range.index;
    this.$emit("imageadded", file, Editor, cursorLocation, resetUploader);
}
Vue.component('vue-editor', VueEditor);

const mixin = {
    store,
    methods: {
        systemMessage(type = 'success', options = {}) {
            options = Object.assign(
                {
                    group: 'system',
                    type: type,
                    title: type == 'success' ? this.trans('crud.success') : this.trans('crud.error'),
                    text: type == 'success' ? this.trans('crud.action_completed') : this.trans('crud.action_failed')
                },
                options
            );
            this.$notify(options);
        }
    },
};
Vue.mixin(MixinsTrans);
Vue.mixin(mixin);
