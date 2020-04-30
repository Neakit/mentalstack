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
            async makeOrder({ state, commit }, { id, phone, title = '', description = ''} ) {
                const url = 'https://api.trello.com/1/cards';
                const orderMessage = 'id: ' + id + 'Наименование: ' + title + 'Описание: ' + description;
                const qs = {
                    name: phone,
                    desc: orderMessage,
                    pos: 'bottom',
                    idList: '5d063c6c414b4355e593aa79',
                    idMembers: '5c1ecd3be3f04424b80bb26b',
                    keepFromSource: 'all',
                    key: '8f8bdc084c598239903b33ddf9a06bca',
                    token: 'a59594d1e3fb7432f4d94d8bd1870535770fdd07c3490826b0359cbe09e4ac70'
                }
                try {
                    const { status } = await axios.post(
                        url,
                        qs,
                        { crossdomain: true }
                    );
                    if(status === 200) {
                        debugger
                    }
                } catch (e) {
                    console.log(e);
                }
            }
        },
        modules: {
        }
    }
);
