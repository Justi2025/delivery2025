/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<script>

export default {
  name: "BarcodeImage",

  emits: ['onClick'],

  props: {
    name: {
      type: String,
      required: false,
    },
    alt: {
      type: String,
      required: false,
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
      this.$emit('onClick', e.target.dataset.image);
    }
  }
}
</script>

<template>
  <img class="img-fluid img-thumbnail barcode-image"
       :alt="alt"
       :data-bs-toggle="toggle"
       :data-bs-target="target"
       :data-image="upload_path(name)"
       :src="upload_path(name)"
       :key="name"
       @click="onClick"
  />
</template>

<style scoped>
.barcode-image {
  width: 32px;
  cursor: pointer;
}
</style>