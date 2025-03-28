/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
const state = () => ({
    usersPageViewType: 'list',
    unreadPostsCount: 0,
    isDarkModeEnabled: false
});

const getters = {}

const mutations = {

    SET_USERS_PAGE_VIEW_TYPE(state, value) {
        state.usersPageViewType = value;
    },

    SET_UNREAD_POSTS_COUNT(state, value) {
        state.unreadPostsCount = value;
    },

    SET_DARK_MODE_STATE(state, value) {
        state.isDarkModeEnabled = value;
    }
}

const actions = {
    async getAndSetUnreadPostsCount({commit} = {}) {
        const {data} = await postsService().unreadPostsCount();
        commit('SET_UNREAD_POSTS_COUNT', data.unread_counts);
    },

    setDarkMode({commit, state} = {}, value) {
        const documentRoot = document.documentElement;
        documentRoot.setAttribute('data-bs-theme', value ? 'dark' : '');
        commit('SET_DARK_MODE_STATE', value);
    },

    async initDarkMode({dispatch, state} = {}) {
        await dispatch('setDarkMode', state.isDarkModeEnabled);
    }
}


export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
