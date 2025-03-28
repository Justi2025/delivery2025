 
import {createStore} from "vuex";
import VuexPersistence from "vuex-persist";
import authStore from "./authStore.js";
import userStore from "./userStore.js";
import appSettingsStore from "./appSettingsStore.js";
import orderStore from "./orderStore.js";
import usersActivityLogsStore from "./usersActivityLogsStore.js";
import {authService, citiesService} from "../api/index.js";
import {employeeService} from "../api/ServisSotrudnikov.js";
import {deliveryPointsService} from "../api/ServisPvzs.js";
import ordersFilterStore from "./ordersFilterStore.js";
import pageSettingsStore from "./pageSettingsStore.js";
import reportsPageStore from "./reportsPageStore.js";
import orderSearcher from "./komponenti/orderSearcher.js";
import {companiesService} from "../api/ServisKompanii.js";


const vuexPersist = new VuexPersistence({
    key: import.meta.env.VITE_APP_NAME.toLowerCase() + '__store',
    storage: window.localStorage
});

const store = createStore({
    state: {
        queryString: null,
        cities: [],
        couriers: [],
        addresses: [],
        companies: [],
        authenticated_user: {},
    },
    getters: {
      filter_addresses(state) {
          return state.addresses.map(a => (
              {
                  id: a.id,
                  value: `${a?.company?.name}, ${a.city}, ${a.street} ${a.house}`,
              }
          ));
      }
    },
    actions: {

        async getProfileData({commit}) {
            const {data} = await authService().getUser();
            commit('SET_AUTHENTICATED_USER', data);
        },

        async loadCities({commit}) {
            const list = await citiesService().poluchitGoroda();
            let cities = list.map(c => ({id: c.id, value: c.name}));
            commit('SET_CITIES', cities);
        },

        async loadCouriers({commit}) {
            const {data} = await employeeService().getCouriers();
            const list = data.data.map(c => ({id: c.id, value: c.full_name}));
            commit('SET_COURIERS', list);
        },

        async loadAddresses({commit}) {
            const {data} = await deliveryPointsService().list('');
            const addresses = data.data.map(a => ({
                ...a,
                value: `${a.city}, ${a.street} ${a.house}`,
            }));
            commit('SET_ADDRESSES', addresses);
        },

        async loadCompanies({commit}) {
            const {data} = await companiesService().spisokPoluchit();
            commit('SET_COMPANIES', data.data);
        }
    },
    mutations: {
        SET_QUERY_STRING(state, qs) {
            state.queryString = qs;
        },

        SET_CITIES(state, payload) {
            state.cities = payload;
        },

        SET_COURIERS(state, payload) {
            state.couriers = payload;
        },

        SET_ADDRESSES(state, payload) {
            state.addresses = payload;
        },

        SET_COMPANIES(state, payload) {
            state.companies = payload;
        },

        SET_AUTHENTICATED_USER(state, data) {
            state.authenticated_user = data;
        },

        UPDATE_USER_ADDRESS(state, {street, house, flat}) {
            state.authenticated_user = {...state.authenticated_user, street, house, flat};
        },

        ADD_COURIER(state, {id, value}) {
            state.couriers = [...state.couriers, {id, value}];
        },

        UPDATE_ADDRESS(state, payload) {
            const addresses = state.addresses.filter(a => a.id !== payload.id);
            state.addresses = [...addresses, {
                ...payload,
                value: `${payload.city}, ${payload.street} ${payload.house}`
            }];
        },
    },
    modules: {
        authStore,
        userStore,
        appSettingsStore,
        orderStore,
        usersActivityLogsStore,
        ordersFilterStore,
        pageSettingsStore,
        reportsPageStore,
        orderSearcher
    },
    plugins: [vuexPersist.plugin]
});

export default store;
