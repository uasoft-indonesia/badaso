import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from './views/home.vue';
import About from './views/about.vue';

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/home',
            name: 'home',
            component: Home
        },
        {
            path: '/about',
            name: 'about',
            component: About
        }
    ]
})

export default router;