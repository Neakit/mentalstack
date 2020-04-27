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
import Dashboard from "../pages/Dashboard";

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
                    name: 'admin.dashboard'
                }
            ]
        }
    ]
});

export default router;
