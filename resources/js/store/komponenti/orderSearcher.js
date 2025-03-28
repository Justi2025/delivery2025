
const searchParamsBuilder = () => ({
    term: '',
    type: ''
});

const initialState = () => ({
    waiting_for_customers: searchParamsBuilder()
});

export default {
    namespaced: true,

    state: initialState,

    getters: {
        getSearchParams: (state) => (id) => {
            return state[id] || initialState()[id];
        },
    },

    actions: {
        setSearchParams({commit}, {id, params}) {
            commit('SET_SEARCH_PARAMS', {id, params});
        },
        clearSearchParams({commit}, id) {
            commit('CLEAR_SEARCH_PARAMS', id);
        },
    },

    mutations: {
        SET_SEARCH_PARAMS(state, payload) {
            state[payload.id] = {...state[payload.id], ...payload.params};
        },
        CLEAR_SEARCH_PARAMS(state, id) {
            state[id] = initialState()[id];
        },
    }
}