import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
        state: {
        },
        getters: {
        },
        mutations: {
        },
        actions: {

            /**
             * Получение категорий продуктов
             * по умолчанию возвращает все категории
             * @param state
             * @param payload
             * @returns {Promise}
             */
            getCategories(state, payload) {
                const defaultParams = {
                    start: 0,
                    length: 10,
                    currentPage: 1,
                    filter: "",
                    sortRow: "id",
                    sort: 'desc'
                };
                const payloadParams = payload || {};
                const params = { ...defaultParams, ...payloadParams };
                return axios.get('/admin/categories/get-records-with-params', { params });
            },

            /**
             * Получение моделей продукта
             * @param state
             * @param payload
             * @returns {Promise}
             */
            getProductModels(state, payload) {
                const defaultParams = {
                    start: 0,
                    length: 10,
                    currentPage: 1,
                    filter: "",
                    sortRow: "id",
                    sort: 'desc'
                };
                const payloadParams = payload || {};
                const params = { ...defaultParams, ...payloadParams };
                return axios.get('/admin/product-models/get-records-with-params', { params });
            }
        },
        modules: {
        }
    }
);
