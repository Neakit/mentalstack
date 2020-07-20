<template>
    <div>
        breadcrumbs
    </div>
</template>

<script>
    export default {
        name: 'breadcrumbs',
        created() {
            this.setBreadcrumbs();
        },
        methods: {
            setBreadcrumbs() {
                console.log(this.$route);
            }
        }
    }
</script>