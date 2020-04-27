import Vue       from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

/**
 * Import Layouts .vue files
 */
import DefaultLayout from "../layouts/DefaultLayout";

/**
 * Import pages .vue files
 */
import Home from "../pages/Home";

/**
 * Create VueRouter instance
 * @type {VueRouter}
 */
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'web',
            component: DefaultLayout,
            children: [
                {
                    path: 'home',
                    component: Home,
                    name: 'web.home'
                }
            ]
        }
    ]
});

export default router;
