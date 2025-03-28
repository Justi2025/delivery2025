/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
    <div :id="`nav-${name}`" :aria-labelledby="`nav-${name}-tab`" class="tab-pane fade" role="tabpanel" tabindex="0">
        <slot>...</slot>
    </div>
</template>

<script>
export default {
    name: "NavTabPane",
    props: {
        name: {
            type: String,
            required: false
        }
    },
}
</script>

<style scoped>

</style>
