<template>
    <v-row justify="center">
        <v-dialog v-model="dialog" max-width="600">
            <v-card>
                <v-card-title class="headline">Оставить заявку</v-card-title>

                <v-card-text>
                    Let Google help apps determine location. This means sending anonymous location data to Google, even when no apps are running.
                </v-card-text>

                <v-card-text>
                    <div class="col-12 p-1">
                        <v-row>
                            <v-col cols="12">
                                Введите Ваш номер:
                            </v-col>
                            <v-col cols="12">
                                <masked-input
                                    class="form-control"
                                    v-model="phone"
                                    mask="\+\7 (111) 111-11-11"
                                    placeholder="+7 (9xx) xxx - xx - xx"
                                />
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-text-field
                                label="Модель"
                                type="text"
                                class="form-control"
                                id="category-title-form"
                                :value="order.title"
                                disabled
                            />
                        </v-row>
                        <v-row>
                            <v-textarea
                                label="Описание"
                                class="form-control"
                                id="category-description-form"
                                rows="3"
                                :value="order.description"
                                disabled
                            />
                        </v-row>
                    </div>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="makeOrderWrap"
                    >
                        Оформить заказ
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
    import { mapActions } from 'vuex';
    import MaskedInput from 'vue-masked-input';
    export default {
        props: {
            value: {
                type: Boolean
            },
            order: {
                type: Object,
                required: true,
                default: () => ({
                    title: '',
                    description: ''
                }),
            },
        },
        computed: {
            dialog: {
                get() {
                    return this.value;
                },
                set(val) {
                    this.$emit('input', val);
                }
            }
        },
        components: {
            MaskedInput
        },
        data() {
            return {
                phone: '',
            }
        },
        methods: {
            ...mapActions(['makeOrder']),
            makeOrderWrap() {
                this.makeOrder({
                    id: 54,
                    phone: this.phone,
                    description: this.order.description,
                    title: this.order.title
                });
            }
        }
    }
</script>

