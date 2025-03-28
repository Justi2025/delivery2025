/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
export default {
    namespaced: true,

    state: () => ({
        selected_report_type: 'by_day',
        selected_office_id: 0,
        selected_day: null
    }),

    getters: {

    },

    actions: {},

    mutations: {
        SET_SELECTED_REPORT_TYPE(state, report_type) {
            state.selected_report_type = report_type;
        },

        SET_SELECTED_OFFICE_ID(state, office_id) {
            state.selected_office_id = office_id;
        },

        SET_SELECTED_DAY(state, day) {
            state.selected_day = day;
        },
    }
}
