
<script>
export default {
  name: "BarcodesButton",

  emits: ['onClick'],

  props: {
    barcodes: {
      type: Array,
      required: true,
    },
    toggle: {
      type: String,
      required: false,
      default: 'modal'
    },
    target: {
      type: String,
      required: true,
    },
  },

  methods: {
    onClick(e) {
      e.stopPropagation();
      this.$emit('onClick', this.barcodes);
    }
  }
}
</script>

<template>
<div class="btn btn-sm btn-warning"
     @click="onClick"
     data-bs-toggle="modal"
     :data-bs-target="target"
>
  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upc"
       viewBox="0 0 16 16">
    <path
        d="M3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0z"/>
  </svg>
</div>
</template>

<style scoped>

</style>