<template>
    <v-card class="ma-2" outlined>
        <v-data-table
            :headers="headers"
            :items="items"
            :options.sync="options"
            :server-items-length="totalItems"
            class="elevation-1"
        >
            <template v-slot:top>
                <v-toolbar flat color="white">
                    <v-toolbar-title>Товары запчастей</v-toolbar-title>
                    <v-spacer/>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                    />
                    <v-spacer/>
                    <v-dialog v-model="dialog" max-width="700px">
                        <template v-slot:activator="{ on }">
                            <v-btn color="primary" dark class="mb-2" v-on="on">Создать</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">Новый продукт</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-file-input
                                                chips
                                                multiple
                                                @change="addFiles($event)"
                                                accept="image/png, image/jpeg, image/bmp"
                                                placeholder="Добавьте фото"
                                                prepend-icon="mdi-camera"
                                                label="Avatar"
                                            />
                                        </v-col>

                                        <v-col>
                                            <v-slide-group multiple show-arrows>
                                                <v-slide-item
                                                    v-for="(img, index) in preview"
                                                    :key="index"
                                                    v-slot:default="{ active, toggle }"
                                                >
                                                    <v-card
                                                        width="150px"
                                                        class="ma-2"
                                                    >
                                                        <v-card-actions>
                                                            <v-spacer></v-spacer>
                                                            <v-icon @click="removeImage(img)">close</v-icon>
                                                        </v-card-actions>
                                                        <v-img
                                                            class="white--text align-end"
                                                            height="150px"
                                                            :src="img.url"
                                                        >
                                                        </v-img>
                                                        <v-card-subtitle class="pb-0">{{ img.name }}</v-card-subtitle>
                                                    </v-card>
                                                </v-slide-item>
                                            </v-slide-group>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field v-model="editedItem.title" label="Title"></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field v-model="editedItem.product_number" label="Номер продукта"></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field v-model="editedItem.product_number_replacements" label="Номера замены"></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field v-model="editedItem.product_number_inner" label="Внутренний номер"></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-textarea v-model="editedItem.description" label="Описание"></v-textarea>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field type="number" v-model="editedItem.price" label="Цена"></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-select
                                                v-model="editedItem.category_id"
                                                :items="categories"
                                                item-text="title"
                                                item-value="value"
                                                label="Категория"
                                            />
                                        </v-col>
                                        <v-col cols="12">
                                            <v-select
                                                v-model="editedItem.product_model_id"
                                                :items="productModels"
                                                item-text="title"
                                                item-value="value"
                                                label="Модель"
                                            />
                                        </v-col>
                                        <v-col cols="12">
                                            <v-checkbox
                                                :true-value="1"
                                                :false-value="0"
                                                v-model="editedItem.product_recommend" label="Рекомендовать" />
                                        </v-col>
                                    </v-row>
                                </v-container>
                                <v-card-actions class="pb-2">
                                    <v-spacer></v-spacer>
                                    <v-btn color="warning" :loading="loading.saving" @click="closeModal">Отмена</v-btn>
                                    <v-btn color="primary" :loading="loading.saving" @click="save">Сохранить</v-btn>
                                </v-card-actions>
                            </v-card-text>
                        </v-card>
                    </v-dialog>

                </v-toolbar>
            </template>

            <template v-slot:item.product_recommend="{ item }">
                {{ item.product_recommend ? 'в рекомендациях' : 'нет в рекомендациях' }}
            </template>

            <template v-slot:item.created_at="{ item }">
                {{ item.created_at | dateFormat }}
            </template>

            <template v-slot:item.edit="{ item }">
                <v-btn variant="primary" @click="openModal(item)">edit</v-btn>
            </template>

            <template v-slot:item.delete="{ item }">
                <v-dialog v-model="confirmDeleteDialog" persistent max-width="290">
                    <v-card>
                        <v-card-title class="headline">Удаление продукта</v-card-title>
                        <v-card-text>Вы уверены, что хотите удалить этот продукт?</v-card-text>
                        <v-card-actions>
                            <v-btn color="red darken-1" :loading="loading.deleting" text @click="deleteProduct(item)">Удалить</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" :loading="loading.deleting" text @click="confirmDeleteDialog = false">Отмена</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-btn variant="primary" @click="confirmDeleteDialog = true">delete</v-btn>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
    import { mapActions } from "vuex";

    export default {
        data () {
            return {
                page: 1,
                rules: [
                    value => !value || value.size < 8000000 || 'Avatar size should be less than 2 MB!',
                ],
                dialog: false,
                confirmDeleteDialog: false,
                search: '',

                editedItem: {
                    id: 0,
                    files: [],
                    title: '',
                    product_number: '',
                    product_number_replacements: '',
                    product_number_inner: '',
                    description: '',
                    price: 0,
                    category: null,
                    category_id: null,
                    product_model_id: null,
                    product_recommend: 0,
                    images: []
                },
                headers: [
                    { text: 'id', align: 'start', sortable: true, value: 'id' },
                    { text: 'Название', value: 'title' },
                    { text: 'Номер детали', value: 'product_number' },
                    { text: 'Номер замены', value: 'product_number_replacements' },
                    { text: 'Внутрений номер', value: 'product_number_inner' },
                    { text: 'Описание', value: 'description' },
                    { text: 'Цена', value: 'price' },
                    { text: 'Категория', value: 'category'},
                    { text: 'Модель', value: 'product_model'},
                    // { text: 'Фото', value: 'images'},
                    { text: 'Рекомендация', value: 'product_recommend'},
                    { text: 'Создан', value: 'created_at' },
                    { text: 'edit', value: 'edit'},
                    { text: 'delete', value: 'delete'},
                ],
                items: [],
                totalItems: 0,
                options: {},
                preview: [],

                categories: [],
                productModels: [],

                loading: {
                    deleting: false,
                    saving: false
                },
            }
        },
        watch: {
            dialog (val) {
                val || this.closeModal()
            },
            options: {
                handler () {
                    this.getProducts()
                },
                deep: true,
            },
        },
        mounted() {
            this.getCategories().then(res => {
                this.categories = res.data.data.map(i => {
                    return {
                        value: i.id,
                        title: i.title
                    }
                })
            });
            this.getProductModels().then(res => {
                this.productModels = res.data.data.map(i => {
                    return {
                        value: i.id,
                        title: i.title
                    }
                })
            });
            this.getProducts();
        },
        methods: {
            ...mapActions(['getCategories', 'getProductModels']),
            /**
             *  preload images
             */
            addFiles(files) {
                if(files.length !== 0) {
                    this.editedItem.files = [ ...this.editedItem.files, ...files];

                    this.editedItem.files = this.editedItem.files.map((f, index) => {
                        f.id = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
                        f.url = URL.createObjectURL(f);
                        f.itemType ='objectUrl';

                        return f;
                    });
                    this.renderPreview();
                }
            },

            /**
             * Render images
             */
            renderPreview() {
                this.preview = [...this.editedItem.files, ...this.editedItem.images];
            },
            /**
             * Удалить изображение в форме
             */
            removeImage(item) {
                switch (item.itemType) {
                    case 'objectUrl':
                        this.editedItem.files = this.editedItem.files.filter(f => f.id !== item.id);
                        break;
                    case 'pathUrl':
                        this.editedItem.images = this.editedItem.images.filter(f => f.id !== item.id);
                        break;
                }
                this.renderPreview();
            },

            /**
             * Функция-распределитель (создать новый / отредактировать продукт)
             */
            save() {
                switch (Boolean(this.editedItem.id)) {
                    case false:
                        this.createProduct();
                        break;
                    case true:
                        this.editProduct();
                        break;
                }
            },
            /**
             * Создание продукта
             * @returns {Promise}
             */
            createProduct() {
                this.loading.saving = true;
                let formData = new FormData()

                for(const key in this.editedItem) {
                    if(key === 'files') {
                        for(const index in this.editedItem[key]) {
                            formData.append(`files[${index}]`, this.editedItem[key][index]);
                        }
                    } else {
                        formData.append(key, this.editedItem[key])
                    }
                }

                return axios({
                    data: formData,
                    config: { headers: {'Content-Type': 'multipart/form-data' }},
                    url: '/admin/products/create',
                    method: 'post',

                }).then(res => {
                    this.getProducts();
                }).catch(e => {
                    console.error('createProduct method error', this, e)
                }).finally(() => {
                    this.closeModal();
                    this.loading.saving = false;
                })
            },
            /**
             * Редактирование продукта
             * @returns {Promise}
             */
            editProduct() {
                this.loading.saving = true;
                let formData = new FormData();

                for(const key in this.editedItem) {
                    switch(key) {
                        case 'files':
                            for(const index in this.editedItem[key]) {
                                formData.append(`files[${index}]`, this.editedItem[key][index]);
                            }
                            break;
                        case 'images':
                            let images = this.editedItem[key].length > 0 && this.editedItem[key].map(i => i.name) || [];
                            images = JSON.stringify(images);
                            formData.append('images', images)
                            break;
                        default:
                            formData.append(key, this.editedItem[key])
                    }
                }

                return axios({
                    url: `/admin/products/edit/${this.editedItem.id}`,
                    method: 'post',
                    config: { headers: {'Content-Type': 'multipart/form-data' }},
                    data: formData
                }).then(res => {
                    this.getProducts();
                }).catch(e => {
                    console.error('editProduct method error', this, e)
                }).finally(() => {
                    this.closeModal();
                    this.loading.saving = false;
                })
            },
            /**
             * Удаление продукта
             * @returns {Promise}
             */
            deleteProduct(item) {
                this.loading.deleting = true;
                return axios({
                    url: `/admin/products/delete/${item.id}`,
                    method: 'get',
                }).then(res => {
                    this.getProducts();
                }).catch(e => {
                    console.error('getProducts method error', e)
                }).finally(() => {
                    this.confirmDeleteDialog = false;
                    this.loading.deleting = false;
                })
            },
            /**
             * Открыть модальное окно
             */
            openModal(item) {
                this.editedItem = Object.assign({}, item);
                this.renderPreview();
                this.dialog = true
            },
            /**
             * Закрыть модальное окно
             * при закрытии окна объект формы становится в default
             */
            closeModal() {
                this.dialog = false;
                this.editedItem = Object.assign({}, {
                    id: 0,
                    files: [],
                    title: '',
                    product_number: '',
                    product_number_replacements: '',
                    product_number_inner: '',
                    description: '',
                    price: 0,
                    category_id: null,
                    product_model_id: null,
                    product_recommend: '1',
                    images: []
                })
            },
            /**
             * Полуение продуктов магазина
             * @returns {Promise}
             */
            getProducts() {
                const { sortBy, sortDesc, page, itemsPerPage } = this.options
                return axios.get('/admin/products/get-records-with-params', {
                    params: {
                        start: (page - 1) * itemsPerPage,
                        length: (page - 1) * itemsPerPage + itemsPerPage,
                        currentPage: page,
                        filter: "",
                        sortRow: "id",
                        sort: 'desc'
                    }
                }).then(res => {
                    this.totalItems = res.data.count;
                    this.items = res.data.data.map(p => {
                        p.images = p.images !== null && p.images.length > 0 && p.images.map(i => {
                            return {
                                name: i,
                                url: `/images/products/${i}`,
                                id: Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15),
                                itemType: 'pathUrl'
                            }
                        }) || [];

                        return {
                            ...p,
                            files: [],
                            category: p.category && p.category.title || '',
                            product_model: p.product_model && p.product_model.title || '',
                            images: p.images,
                        }
                    })
                }).catch(e => {
                    console.error('getProducts method Products component', this, e)
                })
            },
        }
    }
</script>
