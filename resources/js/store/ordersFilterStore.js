 
import OptsiiSdelok from "../api/sdelki/OptsiiSdelok.js";
import optsciiSortirovkiSdelok from "../stranitsi/sdelki/obschie/optsciiSortirovkiSdelok.js";

const defaultSortOrder = optsciiSortirovkiSdelok[5].sort_order;

const filtersAndSortBuilder = () => {
    return {
        filters: {
            customer_id: null,
            order_id: null,
            couriers: [],
            order_statuses: [],
            addresses_from: [],
            addresses_to: [],
            start_datetime: '',
            end_datetime: '',
            customer_cities: []
        },
        sort: defaultSortOrder
    }
}

const initialState = () => ({
    all_orders: filtersAndSortBuilder(),
    pending: filtersAndSortBuilder(),
    accepted: filtersAndSortBuilder(),
    assigned_to_courier: filtersAndSortBuilder(),
    received_by_courier: filtersAndSortBuilder(),
    issued: filtersAndSortBuilder(),
    cancelled: filtersAndSortBuilder(),
});

export default {
    namespaced: true,

    state: initialState,

    getters: {
        getRouterQuery(state, getters, rootState) {
            return rootState.queryString;
        },
        getFiltersForPage: (state) => (page) => {
            return state[page]?.filters || initialState()[page]?.filters;
        },
        getSortForPage: (state) => (page) => {
            return state[page]?.sort || initialState()[page]?.sort;
        },
        isFilterEmpty: (state) => (page) => {
            const {
                customer_id, order_id, couriers,
                order_statuses, addresses_to,
                addresses_from, start_datetime,
                end_datetime, customer_cities
            } = state[page]?.filters;

            return !customer_id && !order_id && couriers.length === 0 &&
                order_statuses.length === 0 && addresses_to.length === 0 &&
                addresses_from.length === 0 && customer_cities.length === 0 &&
                !start_datetime && !end_datetime;
        },
        getFiltersAndSortOptions: (state, getters) => (page) => {

            if (!page)
                throw new Error('Page argument mandatory');

            const opts = new OptsiiSdelok();

            opts.query = getters.getRouterQuery;
            opts.filters = {
                customer_id: state[page]?.filters.customer_id,
                order_id: state[page]?.filters.order_id,
                couriers: state[page]?.filters.couriers?.map(c => c.id),
                order_statuses: state[page]?.filters.order_statuses?.map(os => os.id),
                addresses_from: state[page]?.filters.addresses_from?.map(a => a.id),
                addresses_to: state[page]?.filters.addresses_to?.map(a => a.id),
                start_datetime: state[page]?.filters.start_datetime && Date.parse(state[page]?.filters.start_datetime),
                end_datetime: state[page]?.filters.end_datetime && Date.parse(state[page]?.filters.end_datetime),
                customer_cities: state[page]?.filters.customer_cities?.map(c => c.id),
            };

            const [key, value] = state[page]?.sort?.split('=');
            opts.sort = ({[key]: value});
            return opts;
        }
    },

    actions: {
        setFiltersForPage({commit, state}, {page, filters}) {
            commit('SET_FILTERS_FOR_PAGE', {page, filters});
        },
        clearFiltersForPage({commit}, page) {
            commit('CLEAR_FILTERS_FOR_PAGE', page);
        },
        setSortForPage({commit}, {page, sort}) {
            commit('SET_SORT_FOR_PAGE', {page, sort});
        }
        // Add other actions as needed
    },

    mutations: {
        SET_FILTERS_FOR_PAGE(state, payload) {
            state[payload.page].filters = {...state[payload.page].filters, ...payload.filter};
        },
        CLEAR_FILTERS_FOR_PAGE(state, page) {
            // Todo: sort option should be cleared too
            state[page].filters = initialState()[page]?.filters;
        },
        SET_SORT_FOR_PAGE(state, payload) {
            state[payload.page].sort = payload.sort;
        }
        // Add other mutations as needed
    }
};
