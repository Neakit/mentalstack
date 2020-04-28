<template>
    <v-content>
        <v-container class="fill-height" fluid>
            <v-row align="center" justify="center">
                <v-col cols="12" sm="8" md="4">
                    <v-card class="elevation-12">
                        <v-toolbar color="primary" dark flat>
                            <v-toolbar-title>Вход в панель админа</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-form
                                ref="form"
                                v-model="valid"
                                lazy-validation
                            >
                                <v-text-field
                                    label="Login"
                                    name="login"
                                    prepend-icon="person"
                                    type="text"
                                    v-model="form.email"
                                    :rules="rules"
                                />

                                <v-text-field
                                    id="password"
                                    label="Password"
                                    name="password"
                                    prepend-icon="lock"
                                    type="password"
                                    v-model="form.password"
                                    :rules="rules"
                                />
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer />
                            <v-btn
                                color="primary"
                                @click="validate"
                                :disabled="!valid"
                                :loading="loading"
                            >Войти</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-content>
</template>

<script>
    export default {
        data() {
            return {
                valid: true,
                form: {
                    email: '',
                    password: ''
                },
                loading: false,
                rules: [(val) => !!val || 'Поле обязательно к заполнению']
            }
        },
        methods: {
            /**
             * Validate login form
             */
            validate () {
                if(this.$refs.form.validate()) {
                    this.login();
                }
            },
            /**
             * Login admin user
             */
            login() {
                this.loading = true;
                axios({
                    method: 'post',
                    url: '/admin/login',
                    data: {
                        email: this.form.email,
                        password: this.form.password
                    }
                }).then(res => {
                    if(res.data.success) {
                        this.$router.push('dashboard');
                    }
                }).catch(e => {
                    console.error('login layout component', e.response);
                }).finally(() => {
                    this.loading = false;
                })
            }
        }
    }
</script>
