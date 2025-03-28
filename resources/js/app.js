/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
import {createApp} from 'vue';
import store from "./store/index.js";
import App from './App.vue';
import router from './router';
import '../css/app.css';
import '../css/dashboard.css';
import mixins from "./utils/mixins.js";
import {vMaska} from 'maska';
import Vue3TouchEvents from "vue3-touch-events";

const app = createApp(App);

app.use(store);
app.use(router);
app.use(Vue3TouchEvents);

app.mixin(mixins);
app.directive('maska', vMaska);

app.mount('#app')