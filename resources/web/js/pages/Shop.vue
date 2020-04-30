<template>
    <v-container>
        <breadcrumbs />

        <v-row>
            <v-col cols="9">
                <v-row>
                    <v-col cols="4" v-for="(product, index) in products" :key="index">
                        <product-card
                            :plain="false"
                            :product-data="product"
                        />
                    </v-col>
                </v-row>
            </v-col>
            <v-col col="3">
                <v-card raised class="mt-3">
                    <v-card-title>
                        Фильр
                    </v-card-title>
                    <v-card-text>
                        Поиск по названию
                        <v-text-field
                            label="Solo"
                            placeholder="Поиск"
                            solo
                            v-model="filter"
                        />
                        Поиск по модели
                        <v-expansion-panels flat>
                            <v-expansion-panel>
                                <v-expansion-panel-header>Выберите модель</v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <v-checkbox
                                        v-for="(model, i) in models"
                                        :key="i"
                                        v-model="model.active"
                                        :label="`${model.title}`"
                                    />
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>

                    </v-card-text>

                    <v-card-text>
                        <v-btn dark color="orange" block @click="search" :loading="loading">Поиск</v-btn>
                        <v-btn class="mt-2" dark color="blue" block @click="search({ empty: true })" :loading="loading">Очистить фильтр</v-btn>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import Breadcrumbs from '../components/breadcrumbs';
    import ProductCard from "../components/ProductCard";

    export default {
        components: {
            'breadcrumbs': Breadcrumbs,
            'product-card': ProductCard
        },
        data() {
            return {
                products: [],
                models: [],
                filter: '',
                loading: false
            }
        },
        methods: {
            search({ empty = false }) {
                this.loading = true;
                const params = {
                    product_model_id: this.models.filter(i => i.active).map(i => i.id),
                    filter: this.filter || ""
                };
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
            }
        },
        created() {
            this.getProducts();
            this.getModels();
        }
    }
</script>
