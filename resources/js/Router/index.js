
import { createRouter, createWebHistory } from 'vue-router';

// import Store from '../Pages/Store.vue';
// import Pos from '../Pages/Pos.vue';
// import Transaction from '../Pages/Transaction.vue';
// import Report from '../Pages/Report.vue';
import Login from '../Pages/Login.vue';
import Register from '../Pages/Register.vue';
import NoPage from '../Pages/NoPage.vue';
// import Category from '../Pages/Category.vue';

import { useStore } from '../Store/Auth';

// created middleware
const authMiddleware = (to, from, next) => {

    // ກວດ token ຈາກ localStorage
    const token = localStorage.getItem('web_token');
    const user = localStorage.getItem('web_user');
    // console.log(user);
    const store = useStore();

    if(token){
        store.setToken(token); 
        store.setUser(JSON.stringify(user));
        next();
        // console.log('middleware next');
    } else {
        // console.log('middleware no ');
        next({
            path: '/login',
            replace: true
        });
    }
        
};

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
        component: ()=>import('../Pages/Store.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'category',
        path: '/cat',
        component: ()=>import('../Pages/Category.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'pos',
        path: '/pos',
        component: ()=>import('../Pages/Pos.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'transaction',
        path: '/transaction',
        component: ()=>import('../Pages/Transaction.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'report',
        path: '/report',
        component: ()=>import('../Pages/Report.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: '404',
        path: '/:pathMatch(.*)*',
        component: NoPage,
        meta: {
            middleware: [authMiddleware]
        }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
    scrollBehavior() {
        return { top: 0 }
    }
});


router.beforeEach((to,from,next)=>{
    
    const token = localStorage.getItem('web_token');
    if(to.meta.middleware){
        to.meta.middleware.forEach(middleware=>middleware(to,from,next))
        // console.log(token);
    } else {
        
        if(to.path == '/login' || to.path == '/'){
            if(token){
                next({
                    path:'/store',
                    replace: true
                })
            } else {
                next();
            }
        } else {
            next();
        }
    }
});


export default router;