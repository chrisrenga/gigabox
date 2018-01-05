
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import vueSmoothScroll from 'vue-smoothscroll'
Vue.use(vueSmoothScroll);

window.Event = new Vue();
window.flash = function(message, type = 'success') {
    Event.$emit('flash', message, type);
};
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('flash', require('./components/Flash.vue'));

import App from './components/App.vue';
import router from './routes'

const app = new Vue({
    el: '#app',

    router,

    render: h => h(App),

});
