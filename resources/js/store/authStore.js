/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
import {authService} from "../api/index.js";
import FormStep from "../utils/formStep.js";

const regFormInitialState = () => ({
    codeSent: false,
    formStep: FormStep.PhoneConfirm,
    code: '',
    lastname: '',
    firstname: '',
    middlename: '',
    city_id: '',
    street: '',
    house: '',
    flat: '',
    phone: '',
    year_of_birth: '',
    password: '',
    password_confirmation: '',
});

const recoveryFormInitialState = () => ({
    phone: '',
    code: '',
    password: '',
    password_confirmation: '',
    codeSent: false
});



const initialState = () => ({
    user: {
        exp: 0,
        full_name: '',
        iat: 0,
        iss: '',
        user_id: 0,
        user_role: '',
        user_role_id: 0,
        reports_available: false
    },
    isUserAuthenticated: false,
    access_token: null,
    refresh_token: null,
    regInfo: regFormInitialState(),
    recoveryForm: recoveryFormInitialState(),
});

export default {
    namespaced: true,

    state: initialState(),

    getters: {

        getAccessToken: (state) => state.access_token,

        getRefreshToken: (state) => state.refresh_token,

        user: (state) => state.user,

        isAuthenticated: (state) => state.isUserAuthenticated,

        is_admin: (state) => state.user?.role === 'admin',

        regForm: state => state.regInfo,

        formStep: state => state.regInfo.formStep,

        codeSent: state => state.regInfo.codeSent,

        recoveryForm: state => state.recoveryForm,

    },

    actions: {

        async login({commit}, credentials) {

            try {
                const {data} = await authService().login(credentials);
                commit('SET_AUTH_SUCCESS', data);
                return true;
            } catch (e) {
                throw e;
            }

        },

        async refreshToken({commit}) {
            //const { data } = await authService.
        },

        async logout({commit}) {
            authService().logout().then(() => {
                commit('CLEAR_STATE');
            }).finally(() => {
                const LOCALSTORAGE_KEY = import.meta.env.VITE_APP_NAME.toLowerCase() + '__store';
                window.localStorage.setItem(LOCALSTORAGE_KEY, JSON.stringify({}));
                // window.localStorage.clear();//removeItem('weblearning__store');
            });
        },


    },

    mutations: {

        /**
         * Todo: @see https://stackoverflow.com/questions/42295340/how-to-clear-state-in-vuex-store
         *
         * @param state
         * @constructor
         */
        CLEAR_STATE(state) {
            state = {};
            Object.assign(state, initialState());
            /*state.user = null;
            state.isUserAuthenticated = false;
            state.access_token = null;
            state.refresh_token = null;*/
        },

        SET_AUTH_SUCCESS(state, res) {

            const token_parts = res?.access_token?.split('.');

            // Todo: error silently happens here - debugged 20/03/2024
            if (token_parts.length !== 3) {
                throw new Error('Invalid token come from server!');
            }

            state.user = JSON.parse(atob(token_parts[1]));
            state.isUserAuthenticated = true;
            state.access_token = res.access_token;
            state.refresh_token = res.refresh_token;
        },

        SET_ACCESS_TOKEN(state, token) {
            state.access_token = token;
            //Vue.$set(state, 'token', token);
        },

        SET_REFRESH_TOKEN(state, token) {
            state.refresh_token = token;
            //Vue.$set(state, 'token', token);
        },

        SET_AUTHENTICATED(state, isUserAuthenticated) {
            state.isUserAuthenticated = isUserAuthenticated;
        },

        SET_USER(state, user) {
            state.user = user;
        },

        SET_AVATAR(state, avatar) {
            state.user.avatar = avatar;
        },

        SET_REGISTRATION_FORM_FIELD(state, data) {
            state.regInfo = {...state.regInfo, ...data};
            // console.log(state.regInfo);
        },

        CLEAR_REGISTRATION_FORM(state) {
            state.regInfo = regFormInitialState();
        },

        SET_RECOVERY_FORM_FIELD(state, data) {
            state.recoveryForm = {...state.recoveryForm, ...data};
        },

        CLEAR_RECOVERY_FORM(state) {
            state.recoveryForm = recoveryFormInitialState();
        },
    }
}
