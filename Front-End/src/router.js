import { createRouter, createWebHistory } from 'vue-router';
import HomePage from './pages/HomePage.vue';
import SearchPage from './pages/SearchPage.vue';

const router = createRouter({
history: createWebHistory(),
routes: [
    {
        path: '/',
        name: 'home',
        component: HomePage
    },
    {
        path: '/SearchPage',
        name: 'search',
        component: SearchPage
    },
]
});
export { router };