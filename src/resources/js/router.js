import Vue from 'vue'
import VueRouter from 'vue-router'

import MainContainer from './layout/full/MainContainer.vue';
import Home from './views/home.vue';
import About from './views/about.vue';
import HomePage from './views/HomePage.vue';
import Browse from './views/bread/browse.vue';
import Add from './views/bread/add.vue';

Vue.use(VueRouter)

const router =  new VueRouter({
    mode: 'history',
	routes: [
        {
        // ======================
        // Full Layout
        // ======================
            path: '',
            component: MainContainer,
            // ======================
            // Theme routes / pages
            // ======================

            children: [
                {
                    path: '/badaso-admin/',
                    redirect: '/home'
                },
                {
                    path: '/badaso-admin/home',
                    name: 'dashboard',
                    component: HomePage
                },{
                    path: '/badaso-admin/browse',
                    name: 'bread-browse',
                    component: Browse
                },{
                    path: '/badaso-admin/add',
                    name: 'bread-add',
                    component: Add
                },{
                    path: '/badaso-admin/site-management',
                    name: 'site-management',
                    component: Add
                }

            ]
        },
	]
})

export default router;