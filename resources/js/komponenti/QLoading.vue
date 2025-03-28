
<template>
    <template v-if="status">
        <h3 class="h3">Загрузка данных...</h3>
    </template>
    <template v-else>
        <slot/>
    </template>
</template>

<script>
export default {
    name: "QLoading",
    props: {
        status: {
            type: Boolean
        }
    }
}
</script>

<style scoped>

</style>
