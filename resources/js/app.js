import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import router from './Router';
import SideMenu from './Components/SideBarMenu.vue';
import Pagination from './Components/Pagination.vue';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';


import { createPinia } from 'pinia'

const pinia = createPinia();

const app = createApp(App);
app.use(VueSweetalert2);
app.component('SideMenu', SideMenu);
app.component('Pagination', Pagination);
app.use(pinia);
app.use(router);
app.mount('#app-vue');