<template>
    <div style="background: linear-gradient(0deg,#fff,#f5f5fa);">
        <v-toolbar color="orange darken-1" dark>
            <v-container>
                <v-row no-gutters>
                    <v-col cols="1" class="d-flex align-center">
                        <v-btn icon color="white">
                            <v-icon>menu</v-icon>
                        </v-btn>
                    </v-col>
                    <v-col class="text-center">
                        <v-btn height="60" exact to="/" text>Главная</v-btn>
                        <v-btn height="60" exact to="/info" text>Тех. информация</v-btn>
                        <v-btn height="60" exact to="/delivery" text>Доставка</v-btn>
                        <v-btn height="60" exact to="/guarantee" text>Гарантия</v-btn>
                        <v-btn height="60" exact to="/contacts" text>Контакты</v-btn>
                    </v-col>
                    <v-col cols="1" class="d-flex align-center">
                        <v-btn icon color="white">
                            <v-icon>email</v-icon>
                        </v-btn>
                        <v-btn icon>
                            <v-icon color="white">phone</v-icon>
                        </v-btn>
                    </v-col>
                </v-row>
            </v-container>
        </v-toolbar>
        <router-view/>

    </div>
</template>

<script>
    export default {
        data() {
            return {

            }
        }
    }
</script>
