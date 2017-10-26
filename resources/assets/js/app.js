
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import store from './store';

/**
 * Global event dispatcher
 */
window.events = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('flash', require('./components/Flash.vue'));
Vue.component('google-map', require('./components/Map.vue'));
Vue.component('geo-search', require('./places/components/GeoSearch.vue'));
Vue.component('places', require('./places/components/Places.vue'));
Vue.component('place-profile', require('./places/components/PlaceProfile.vue'));
Vue.component('place-create-form', require('./places/components/PlaceCreateForm.vue'));

window.app = new Vue({
    el: '#app',

    store,

    data: {
        googleApiWasLoaded: false,
        ...app
    },

    methods: {
        init() {
            this.googleApiWasLoaded = true;
        },

        toggleNav() {
            this.$refs.navbarBurger.classList.toggle('is-active');
            this.$refs.navbarMenu.classList.toggle('is-active');
        }
    }
});

import authorizations from './authorizations';

Vue.prototype.isAuthenticated = !! window.app.user;

Vue.prototype.authorize = function (...args) {
    if (! window.app.user) {
        return false;
    }

    if (typeof args[0] === 'string') {
        return authorizations[args[0]](args[1], args[2]);
    }

    return args[0](window.app.user);
}
