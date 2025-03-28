/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
// import isMobile from "../utils/isMobile.js";

const settingsBuilder = () => {
    return {
        //view_type: isMobile() ? 'card' : 'list',
        view_type: 'list',
    }
}

const initialState = () => ({
    all_orders: settingsBuilder(),
    pending: settingsBuilder(),
    accepted: settingsBuilder(),
    assigned_to_courier: settingsBuilder(),
    received_by_courier: settingsBuilder(),
    issued: settingsBuilder(),
    cancelled: settingsBuilder(),
    waiting_for_customers: settingsBuilder(),
});

export default {
    namespaced: true,

    state: initialState,

    getters: {
        getPageSettings: (state) => (page) => {
            return state[page] || initialState()[page];
        },
    },

    actions: {},

    mutations: {
        SET_SETTINGS_FOR_PAGE(state, payload) {
            state[payload.page] = {...state[payload.page], ...payload.settings};
        },
        CLEAR_SETTINGS_FOR_PAGE(state, page) {
            // Todo: sort option should be cleared too
            state[page] = initialState()[page];
        },
    }
};
