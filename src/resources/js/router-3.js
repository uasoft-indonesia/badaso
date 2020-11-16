import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from './views/home.vue';
import About from './views/about.vue';

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
	routes: [
        {
            path: '/home',
            name: 'home',
            index: 30,
            component: Home
        },{
            path: '/about',
            name: 'about',
            index: 31,
            component: About
        }
	]
})

export default router;