import BootstrapVue from 'bootstrap-vue'
import lodash from 'lodash';
import '@fortawesome/fontawesome-free/js/fontawesome';
import Notifications from 'vue-notification';
import VeeValidate from 'vee-validate';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';
import { VueEditor } from "vue2-editor/dist/vue2-editor.common";
import store from "./store/index";

Object.defineProperty(Vue.prototype, '_', { value: lodash });
Vue.component('file-upload', require('./components/FileUpload.vue').default);
Vue.component('import', require('./components/Import.vue').default);
Vue.component('multi-select', require('./components/MultiSelect.vue').default);
Vue.component('vue-dropzone', vue2Dropzone);
Object.defineProperty(Vue.prototype, '_', { value: lodash });
require('./index');
Vue.use(Notifications);
Vue.use(BootstrapVue);
Vue.use(VeeValidate);
Vue.use(flatPickr);
VueEditor.methods.emitImageInfo = function emitImageInfo($event) {
    var resetUploader = function resetUploader() {
        var uploader = document.getElementById("file-upload");
        uploader.value = "";
    };

    var file = $event.target.files[0];
    var Editor = this.quill;
    var range = Editor.getSelection();
    var cursorLocation = range.index;
    this.$emit("imageadded", file, Editor, cursorLocation, resetUploader);
}
Vue.component('vue-editor', VueEditor);

const mixin = {
    store,
    methods: {
        trans: function (key) {
            key = key.replace(/::/, '.');
            return _.get(window.trans, key, key);
        },
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
Vue.mixin(mixin);
