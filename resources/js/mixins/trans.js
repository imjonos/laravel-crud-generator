export default {
    name: 'MixinsTrans',
    methods: {
        trans: function (key) {
            key = key.replace(/::/, '.');
            return _.get(window.trans, key, key);
        }
    }
}
