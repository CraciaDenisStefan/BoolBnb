import { createRouter, createWebHistory } from 'vue-router';
import HomePage from './pages/HomePage.vue';
import SearchPage from './pages/SearchPage.vue';
import SingleApartment from './pages/SingleApartment.vue';

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
        component: SearchPage,
        props: (route) => ({
            n_rooms: route.query.n_rooms,
            n_beds: route.query.n_beds,
            address: route.query.address,
            range: route.query.range,
            services: route.query.services,
        }),
    },
    {
        path: '/determinato_appartamento/:slug',
        name: 'determinato_appartamento',
        component: SingleApartment
    },
]
});
export { router };