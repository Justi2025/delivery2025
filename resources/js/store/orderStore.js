/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
import optsciiSortirovkiSdelok from "../stranitsi/sdelki/obschie/optsciiSortirovkiSdelok.js";
import OptsiiSdelok from "../api/sdelki/OptsiiSdelok.js";

const initialState = () => ({
    filter: {
        customer_id: null,
        couriers: [],
        order_statuses: [],
        addresses_from: [],
        addresses_to: [],
        start_datetime: '',
        end_datetime: '',
        customer_cities: []
    },
    sort: null
})

export default {
    namespaced: true,

    state: () => ({
        filter: {
            customer_id: null,
            couriers: [],
            order_statuses: [],
            addresses_from: [],
            addresses_to: [],
            start_datetime: '',
            end_datetime: '',
            customer_cities: []
        },
        sort: optsciiSortirovkiSdelok[5].sort_order
    }),

    getters: {

        getUrlQuery(state, getters, rootState) {
            return rootState.queryString;
        },

        filter: state => state.filter,

        filterEmpty(state) {
            const {
                customer_id,
                couriers,
                order_statuses,
                addresses_to,
                addresses_from,
                start_datetime,
                end_datetime,
                customer_cities
            } = state.filter;

            return !customer_id &&
                couriers.length === 0 &&
                order_statuses.length === 0 &&
                addresses_to.length === 0 &&
                addresses_from.length === 0 &&
                customer_cities.length === 0 &&
                !start_datetime && !end_datetime;
        },

        getFilterData(state) {
            return {
                customer_id: state.filter.customer_id,
                couriers: state.filter.couriers?.map(c => c.id),
                order_statuses: state.filter.order_statuses?.map(os => os.id),
                addresses_from: state.filter.addresses_from?.map(a => a.id),
                addresses_to: state.filter.addresses_to?.map(a => a.id),
                start_datetime: state.filter.start_datetime && Date.parse(state.filter.start_datetime),
                end_datetime: state.filter.end_datetime && Date.parse(state.filter.end_datetime),
                customer_cities: state.filter.customer_cities?.map(c => c.id),
            };
        },

        sort_option: state => state.sort,

        /**
         *
         * @param state
         * @param getters
         * @return {OptsiiSdelok}
         */
        getFiltersAndSortOptions(state, getters) {
            const opts = new OptsiiSdelok();
            opts.query = getters.getUrlQuery;
            opts.filters = getters.getFilterData;

            const [key, value] = getters.sort_option?.split('=');
            opts.sort = ({[key]: value});
            return opts;
        },
    },

    actions: {},

    mutations: {
        CLEAR_FILTER(state) {
            state.filter = initialState().filter;
            // Object.assign(state.filter, initialState())
        },

        SET_FILTER(state, payload) {
            state.filter = {...state.filter, ...payload};
        },

        SET_SORT_OPTION(state, payload) {
            state.sort = payload;
        }
    }
}
