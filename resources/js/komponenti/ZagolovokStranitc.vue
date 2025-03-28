/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <div class="row pt-3 pb-2 mb-4 mx-1 border-bottom align-items-center g-0">
    <div class="col">
      <h3 class="h3">{{ caption }}</h3>
    </div>
    <div class="col d-flex justify-content-end">
      <slot/>
    </div>
  </div>
</template>

<script>
export default {
  name: "ZagolovokStranitc",
  props: {
    caption: {
      type: String,
      required: true,
      default: 'Нет данных'
    }
  },
  mounted() {
    document.title = this.caption;
  },
  watch: {
    caption(newValue) {
      document.title = newValue;
    }
  }
}
</script>

<style scoped>

</style>
