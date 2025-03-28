/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
    <div class="form-check form-switch">
        <input :id="id" :checked="checked" class="form-check-input" role="switch" type="checkbox" @input="onToggle">
        <label :for="id" class="form-check-label cursor-pointer">
            <slot/>
        </label>
    </div>
</template>

<script>
export default {
    name: "Switcher",
    props: {
        id: {
            type: String,
            required: true,
        },
        checked: {
            type: [Boolean, String],
            required: true
        }
    },

    emits: [
        'update:checked',
    ],

    methods: {
        onToggle(e) {
            this.$emit('update:checked', e.target.checked);
        }
    }
}
</script>

<style scoped>
.form-check-input:checked {
   background-color: var(--bs-warning);
   border-color: var(--bs-warning);
}
</style>
