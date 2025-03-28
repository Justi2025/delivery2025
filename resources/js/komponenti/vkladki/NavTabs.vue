
<template>
    <nav>
        <div :id="id" class="nav nav-tabs" role="tablist">
            <slot/>
        </div>
    </nav>
</template>

<script>
export default {
    name: "NavTabs",

    emits: ['onTabShown'],

    props: {
        id: {
            type: String,
            required: true
        }
    },

    mounted() {
      const tabEl = document.getElementById(this.id);
      tabEl && tabEl.addEventListener('shown.bs.tab', this.onTabShown);
    },

    unmounted() {
      const tabEl = document.getElementById(this.id);
      tabEl && tabEl.removeEventListener('shown.bs.tab', this.onTabShown);
    },

    methods: {
      onTabShown(e) {
        this.$emit('onTabShown', e);
      }
    },
}
</script>

<style scoped>

</style>
