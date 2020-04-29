import Vue       from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

/**
 * Import Layouts .vue files
 */
import LoginLayout   from "../layouts/LoginLayout";
import DefaultLayout from "../layouts/DefaultLayout";

/**
 * Import pages .vue files
 */
import Dashboard  from "../pages/Dashboard";
import Products   from "../pages/Products";
import Categories from "../pages/Categories";
import Models     from "../pages/Models";

/**
 * Create VueRouter instance
 * @type {VueRouter}
 */
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/admin/login',
            name: 'admin-login',
            component: LoginLayout
        },
        {
            path: '/admin/',
            name: 'admin',
            component: DefaultLayout,
            children: [
                {
                    path: 'dashboard',
                    component: Dashboard,
                    name: 'admin.dashboard',
                    meta: {
                        name: 'Главная',
                        icon: 'mdi-home'
                    }
                },
                {
                    path: 'products',
                    component: Products,
                    name: 'admin.products',
                    meta: {
                        name: 'Продукты',
                        icon: 'mdi-car-door'
                    }
                },
                {
                    path: 'models',
                    component: Models,
                    name: 'admin.models',
                    meta: {
                        name: 'Модели',
                        icon: 'mdi-car-info'
                    }
                },
                {
                    path: 'categories',
                    component: Categories,
                    name: 'admin.categories',
                    meta: {
                        name: 'Категории',
                        icon: 'mdi-car-shift-pattern'
                    }
                }
            ]
        }
    ]
});

export default router;
