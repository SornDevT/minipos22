
import { createRouter, createWebHistory } from 'vue-router';

import Store from '../Pages/Store.vue';
import Pos from '../Pages/Pos.vue';
import Transaction from '../Pages/Transaction.vue';
import Report from '../Pages/Report.vue';
import Login from '../Pages/Login.vue';
import Register from '../Pages/Register.vue';
import NoPage from '../Pages/NoPage.vue';

const routes = [
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        path:'/',
        redirect:'/store'
    },
    {
        name: 'store',
        path: '/store',
        component: Store
    },
    {
        name: 'pos',
        path: '/pos',
        component: Pos
    },
    {
        name: 'transaction',
        path: '/transaction',
        component: Transaction
    },
    {
        name: 'report',
        path: '/report',
        component: Report
    },
    {
        name: '404',
        path: '/:pathMatch(.*)*',
        component: NoPage
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
    scrollBehavior() {
        return { top: 0 }
    }
});

export default router;