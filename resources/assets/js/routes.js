import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router);

export default new Router({
    mode: 'history',
    linkActiveClass: 'active',
    routes: [
        {
            path: '/',
            component: require('./components/Channels.vue'),
            props: true,
            children: [

            ]
        },

    ],
})
