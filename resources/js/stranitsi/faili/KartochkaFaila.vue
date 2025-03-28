/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<script>
import format_baitov from "../../utils/format_baitov.js";

export default {
  name: "FileCard",

  emits: ['onRemove'],

  props: {
    file: {
      type: Object,
      required: true
    }
  },

  methods: {
    formatBytes: format_baitov,
    getFileExtension(file) {
      return file.generated_name?.split('.')[1];
    },

    onRemove(e) {
      const {id, name} = e.target.dataset;
      this.$emit('onRemove', {id, name});
    }
  }
}
</script>

<template>
  <div class="card h-100">
    <img :src="'/upload/' + file?.generated_name" class="card-img-top" :alt="file?.original_name">
    <div class="card-body">
      <h5 class="card-title">{{ file?.original_name }}</h5>
    </div>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><span class="text-secondary">Тип: </span>{{ getFileExtension(file) }}</li>
      <li class="list-group-item"><span class="text-secondary">Размер: </span>{{ formatBytes(file.size) }}</li>
      <li class="list-group-item"><span class="text-secondary">Создан: </span>{{ dt_format2(file.created_at) }}</li>
    </ul>
    <div class="card-body">
      <a href="#" class="card-link" :data-id="file.id" :data-name="file.original_name" @click.prevent="onRemove">
        Удалить
      </a>
    </div>
  </div>
</template>

<style scoped>
img {
  max-width: 100px;
}
</style>