import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';
export const strict = false;

export default new Vuex.Store({
    modules: {
    },
    strict: debug,
    mutations: {
        setLoading: (state, data) => {
            state.isLoading = data;
        }
    }
});
