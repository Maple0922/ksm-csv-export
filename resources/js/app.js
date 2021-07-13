import VueRouter from 'vue-router';

import HeaderComponent from "./components/HeaderComponent";
import HomeComponent from "./components/HomeComponent";
import DashboardComponent from "./components/DashboardComponent";

require('./bootstrap');

window.Vue = require('vue').default;

Vue.use(VueRouter);


const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'dashboard',
            component: DashboardComponent
        },
        {
            path: '/home',
            name: 'home',
            component: HomeComponent
        },
    ]
});

Vue.component('HeaderComponent', HeaderComponent);

const app = new Vue({
    el: '#app',
    router
});
