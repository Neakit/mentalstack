import Vue     from 'vue';
import App     from "./App";
import router  from "./router";
import Vuetify from "vuetify/lib";

Vue.use(Vuetify);

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

new Vue({
    el: '#app',
    router,
    vuetify: new Vuetify({
        theme: {
            dark: false
        }
    }),
    components: {
        App
    }
});
