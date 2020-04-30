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
import Home        from "../pages/Home";
import Shop        from "../pages/Shop";
import Guarantee   from "../pages/Guarantee";
import Delivery    from "../pages/Delivery";
import Contacts    from "../pages/Contacts";
import ProductPage from "../pages/ProductPage";

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
                    path: '',
                    component: Home,
                    name: 'web.home'
                },
                {
                    path: 'shop',
                    component: Shop,
                    name: 'web.shop'
                },
                {
                    path: 'guarantee',
                    component: Guarantee,
                    name: 'web.guarantee'
                },
                {
                    path: 'Delivery',
                    component: Delivery,
                    name: 'web.delivery'
                },
                {
                    path: 'Contacts',
                    component: Contacts,
                    name: 'web.contacts'
                },
                {
                    path: 'product/:id',
                    component: ProductPage,
                    name: 'web.ProductPage'
                }
            ]
        }
    ]
});

export default router;
