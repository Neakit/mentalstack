<template>
    <v-container>
        <v-row>
            <v-col col="3">
                <v-card class="mt-3">
                    <v-card-text>
                        Поиск по названию
                        <v-text-field
                            label="Solo"
                            placeholder="Поиск"
                            solo
                            v-model="filter"
                        >
                            <template v-slot:append>
                                <v-btn icon color="#b2b2b2" @click="search()">
                                    <v-icon>search</v-icon>
                                </v-btn>
                            </template>
                        </v-text-field>
                        <v-menu :close-on-content-click="false" 
                                offset-x open-on-hover right
                                v-for="(model, index) in models"
                                :key="index"
                        >
                            <template v-slot:activator="{ on }">
                                <v-list-item v-on="on" @click="filterByModel(model)">
                                    <v-list-item-title>{{ model.title }}</v-list-item-title>
                                </v-list-item>
                            </template>
                            <v-list>
                                <v-list-item
                                    v-for="(category, index) in categories"
                                    :key="index"
                                    @click="filterByCategory(model, category)"
                                >
                                    <v-list-item-title>{{ category.title }}</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-card-text>

                    <!-- <v-card-text>
                        <v-btn dark color="orange" block @click="search" :loading="loading">Поиск</v-btn>
                        <v-btn class="mt-2" dark color="blue" block @click="search({ empty: true })" :loading="loading">Очистить фильтр</v-btn>
                    </v-card-text> -->
                </v-card>
            </v-col>
            <v-col cols="9">
                <v-row>
                    <v-col cols="12" v-for="(product, index) in products" :key="index">
                        <product-card
                            :plain="true"
                            :product-data="product"
                        />
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="9" offset="3">
                <div class="text-center">
                    <v-pagination
                        color="#ff9800"
                        v-model="page"
                        :length="15"
                        :total-visible="7"
                    ></v-pagination>
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import ProductCard from "../components/ProductCard";

    export default {
        components: {
            'product-card': ProductCard
        },
        data() {
            return {
                products: [],
                models: [],
                categories: [],
                loading: false,
                show2: false,

                filter: '',
                product_model_id: null,
                category_id: null,

                page: 1
            }
        },
        methods: {
            clearFilter() {
                this.product_model_id = null;
                this.category_id = null;
            },
            filterByModel(model) {
                this.clearFilter();
                this.product_model_id = [ model.id ];
                this.search(); 
            },
            filterByCategory(model, category) {
                this.clearFilter();
                this.product_model_id = [ model.id ];
                this.category_id = [ category.id ];
                this.search(); 
            },

            search(empty = false) {
                this.loading = true;
                const params = {};
                if(this.product_model_id) params.product_model_id = this.product_model_id;
                if(this.category_id) params.category_id = this.category_id;
                if(this.filter.length > 0) params.filter = this.filter;

                if(empty) {
                    this.filter = '';
                    this.models.forEach(i => i.active = false);
                }
                axios({
                    method: 'post',
                    url: '/products/filter-records',
                    params: empty ? {} : params
                }).then(res => {
                    console.log(res.data.data);
                    this.products = this.formatProductData(res.data.data);
                }).catch(e => {
                    console.error(e)
                }).finally(() => {
                    this.loading = false;
                })
            },
            getProducts() {
                axios({
                    method: 'get',
                    url: '/products/get-records-with-params'
                }).then(res => {
                    this.products = this.formatProductData(res.data.data);
                }).catch(e => {
                    console.error(e)
                });
            },
            formatProductData(data) {
                return data.map(p => {
                    p.images = p.images !== null && p.images.length > 0 && p.images;
                    p.images = Object.values(p.images).map(i =>  ({ src: `/images/products/${i}` }) ) || [];
                    return {
                        ...p,
                        category: p.category && p.category.title || '',
                        product_model: p.product_model && p.product_model.title || '',
                        images: p.images,
                    }
                })
            },
            getModels() {
                const params = {
                    start: 0,
                    length: 10,
                    currentPage: 1,
                    filter: "",
                    sortRow: "id",
                    sort: 'desc'
                };
                axios.get('/product-models/get-records-with-params', { params }).then(res => {
                    this.models = res.data.data;
                })
            },
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
                axios.get('/categories/get-records-with-params', { params }).then(res => {
                    this.categories = res.data.data;
                })
            }
        },
        created() {
            this.getProducts();
            this.getModels();
            this.getCategories();
        }
    }
</script>
