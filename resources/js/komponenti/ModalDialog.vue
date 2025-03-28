/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <div :id="id" :aria-labelledby="labelledById" aria-hidden="true" class="modal fade" data-bs-backdrop="static"
       tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 :id="labelledById" class="modal-title fs-5">{{ title }}</h1>
          <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
        </div>
        <div class="modal-body">
          <slot/>
        </div>
        <div class="modal-footer" v-if="$slots.footer">
          <slot name="footer"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: "ModalDialog",
  props: {
    title: {
      type: String,
      required: true
    },
    id: {
      type: String,
      required: true
    },
    size: {
      type: String,
      default: 'xl'
    }
  },


  computed: {
    labelledById() {
      return this.id + 'Label';
    }
  },
}
</script>

<style scoped>

</style>
