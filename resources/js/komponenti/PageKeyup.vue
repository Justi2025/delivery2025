/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
    <div></div>
</template>

<script>
export default {
    name: "PageKeyup",
    emits: ['keyup'],
    created() {
        const component = this;
        this.handler = function (e) {
            component.$emit('keyup', e);
        }
        window.addEventListener('keyup', this.handler);
    },
    beforeDestroy() {
        window.removeEventListener('keyup', this.handler);
    }
}
</script>

<style scoped>
div {
    display: none;
}
</style>
