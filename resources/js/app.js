/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue';
import VueSweetalert2 from 'vue-sweetalert2';
import LoadingOverlay from 'vue-swal2-loading-overlay';

Vue.use(VueSweetalert2);
Vue.use(LoadingOverlay);


import VModal from 'vue-js-modal';
Vue.use(VModal);

import Paginate from 'vuejs-paginate';
Vue.component('paginate', Paginate);

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import store from './store/index';

/** Vuex Configuration **/
import Vuex from 'vuex';
Vue.use(Vuex);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

import general from './components/templates/general.vue';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import { routes } from "./router";
const router = new VueRouter({routes});

const app = new Vue({
    el: '#app',
    router,
    store,
    components: { 'app': general }
});
