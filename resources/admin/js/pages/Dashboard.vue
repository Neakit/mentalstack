<template>
    <v-card
        tile
        class="ma-3"
    >
        <v-list-item>
            <v-list-item-content>
                <v-list-item-title>TODO:</v-list-item-title>
            </v-list-item-content>
        </v-list-item>

        <v-list-item v-for="(item, i) in items" :key="i">
            <v-list-item-content>
                <v-list-item-title>- {{ item }}</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
    </v-card>
</template>

<script>
    export default {
        data() {
            return {
                items: [
                    'DRY у компонентов pages - вынести в рутовый компонент от которого екстендиться',
                    'вынести запросы данных в рутовый компонент',
                    'Валидация полей, чтобы не вылезала ошибки моделей',
                    'удалить bootstrap.js',
                    'удалить неиспользуемые пакеты',
                    'поиск на главной',
                    'динамический импорт webpack'
                ]
            }
        }
    }
</script>
