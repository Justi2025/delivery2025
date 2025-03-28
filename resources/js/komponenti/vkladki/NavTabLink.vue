
<template>
    <button :id="id" :aria-controls="ariaControls" :aria-selected="disabled" :data-bs-target="dataTarget" :disabled="disabled"
            class="nav-link" data-bs-toggle="tab" role="tab" type="button">
        {{ label }}
    </button>
</template>

<script>
export default {
    name: "NavTabLink",
    props: {
        name: {
            type: String,
            required: true
        },
        label: {
            type: String,
            required: true
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        id() {
            return `nav-${this.name}-tab`;
        },

        dataTarget() {
            return `#nav-${this.name}`;
        },

        ariaControls() {
            return `nav-${this.name}`;
        },

        ariaSelected() {
            return ``;
        }
    }
}
</script>

<style scoped>

</style>
