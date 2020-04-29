<template>
    <v-card class="ma-2" outlined>
        <v-data-table
            :headers="headers"
            :items="items"
            :items-per-page="10"
            class="elevation-1"
        >
            <template v-slot:top>
                <v-toolbar flat color="white">
                    <v-toolbar-title>Категории запчастей</v-toolbar-title>
                    <v-spacer/>
                    <v-text-field
                        v-model="params.filter"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                    />
                    <v-spacer/>
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on }">
                            <v-btn color="primary" dark class="mb-2" v-on="on">Создать</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ editedItem.id ? 'Отредактировать' : 'Создать' }} модель продукта</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field v-model="editedItem.title" label="Title"></v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error" dark @click="closeModal" :loading="loading.saving">Отменить</v-btn>
                                <v-btn color="primary" dark @click="save" :loading="loading.saving">Сохранить</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>

            <template v-slot:item.created_at="{ item }">
                {{ item.created_at | dateFormat }}
            </template>

            <template v-slot:item.edit="{ item }">
                <v-btn variant="primary" @click="openModal(item)">edit</v-btn>
            </template>

            <template v-slot:item.delete="{ item }">
                <v-dialog v-model="dialogDelete" max-width="500px">
                    <template v-slot:activator="{ on }">
                        <v-btn color="warning" dark class="mb-2" v-on="on">Удалить</v-btn>
                    </template>
                    <v-card class="pa-2">
                        <v-card-title>
                            <span class="headline">{{ `Вы уверены, что хотите удалить ${item.title} ?` }}</span>
                        </v-card-title>
                        <v-card-actions>
                            <v-btn color="primary" dark @click="deleteCategory(item)" :loading="loading.deleting">Удалить</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="error" dark @click="dialogDelete = false" :loading="loading.deleting">Отменить</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
    import { mapActions } from "vuex";
    import { debounce }   from "lodash";

    export default {
        data () {
            return {
                dialog: false,
                dialogDelete: false,
                loading: {
                    saving: false,
                    deleting: false
                },
                editedItem: {
                    id: 0,
                    title: '',
                },
                headers: [
                    { text: 'id', align: 'start', sortable: true, value: 'id'},
                    { text: 'Название', value: 'title' },
                    { text: 'Создан', value: 'created_at' },
                    { text: 'edit', value: 'edit', sortable: false },
                    { text: 'delete', value: 'delete', sortable: false},
                ],
                items: [],
                params: {
                    start: 0,
                    length: 10,
                    currentPage: 1,
                    filter: "",
                    sortRow: "",
                    sort: 'desc'
                }
            }
        },
        watch: {
            dialog (val) {
                val || this.closeModal()
            },
            'params.filter': {
                handler(val) {
                    if(val && val.length) {
                        this.debounceSearch();
                    } else {
                        this.getProductModels();
                    }
                }
            }
        },

        created() {
            this.getProductModels();
        },

        methods: {
            ...mapActions({ getProductModelsVuex: 'getProductModels'}),
            /**
             * Поиск по таблице
             */
            debounceSearch: debounce(function (e) {
                this.getProductModels();
            }, 1000),
            /**
             * Форматирует модель
             * @returns {Object}
             */
            formatModel(i) {
                return { id: i.id, title: i.title, created_at: i.created_at }
            },
            /**
             * Получить модели продукта
             */
            getProductModels() {
                this.getProductModelsVuex(this.params).then(res => {
                    this.items = res.data.data.map((i) => this.formatModel(i));
                })
            },
            /**
             * Функция-распределитель (сохранить или отредактировать модель продукта)
             */
            save() {
                switch (Boolean(this.editedItem.id)) {
                    case false:
                        this.createProductModel();
                        break;
                    case true:
                        this.editProductModel();
                        break;
                }
            },
            /**
             * Создание модели продукта
             * @returns {Promise}
             */
            createProductModel() {
                this.loading.saving = true;
                return axios({
                    url: '/admin/product-models/create',
                    method: 'post',
                    data: {
                        title: this.editedItem.title,
                    }
                }).then(res => {
                    if(res.data.success) {
                        this.items.push(this.formatModel(res.data.data));
                    }
                }).catch(e => {
                    console.error('createProductModel method error', this, e);
                    this.getProductModels();
                }).finally(() => {
                    this.closeModal();
                    this.loading.saving = false;
                })
            },

            /**
             * Сохранить отредактированную модель продукта
             * @returns {Promise}
             */
            editProductModel() {
                this.loading.saving = true;
                return axios({
                    url: `/admin/product-models/edit/${this.editedItem.id}`,
                    method: 'post',
                    data: {
                        title: this.editedItem.title
                    }
                }).then(res => {
                    if(res.data.success) {
                        const index = this.items.findIndex(i => i.id === res.data.data.id);
                        this.items.splice(index, 1, res.data.data);
                    }
                }).catch(e => {
                    console.error('editProductModel method error', this, e);
                    this.getProductModels();
                }).finally(() => {
                    this.closeModal();
                    this.loading.saving = false;
                })
            },
            /**
             *  "Мягкое" удаление продукта
             * @param item
             * @returns {Promise}
             */
            deleteCategory(item) {
                this.loading.deleting = true;
                return axios({
                    url: `/admin/product-models/delete/${item.id}`,
                    method: 'get',
                }).then(res => {
                    if(res.data.success) {
                        const index = this.items.findIndex(i => i.id === res.data.data.id);
                        this.items.splice(index, 1);
                    }
                }).catch(e => {
                    console.error('deleteCategory method error', this, e)
                    this.getProductModels();
                }).finally(() => {
                    this.dialogDelete = false;
                    this.loading.deleting = false;
                })
            },
            /**
             * Открыть модальное окно создания/редактирования продукта
             * @param item
             */
            openModal(item) {
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },
            /**
             * Закрыть модальное окно создания/редактирования продуктов
             */
            closeModal() {
                this.dialog = false;
                this.editedItem = Object.assign({}, {
                    id: 0,
                    title: '',
                })
            }
        }
    }
</script>
