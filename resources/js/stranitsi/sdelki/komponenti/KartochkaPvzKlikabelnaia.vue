/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<script>
export default {
  name: "DeliveryPointCardClickable",

  emits: ['onClick'],

  props: {
    dp: {
      type: Object,
      required: true
    },
    to: {
      type: String,
      required: true
    },
    selected: {
      type: Boolean,
      required: false
    },
  },

  methods: {
    onClick() {
      this.$emit('onClick', this.dp);
    }
  }
}
</script>

<template>
  <router-link :to="to" :style="{color: `${dp.label_color} !important`}">
  <div
      class="card h-100 cursor-pointer card:hover shadow shadow-sm"
      :class="{'text-bg-primary': selected}"
      :style="{'background-color': dp.color, color: dp.label_color}"
      @click="onClick"
  >
    <div class="card-body d-flex align-items-center justify-content-between">
      <div>
        <div class="fs-6">{{ dp.dp_name }}, {{ dp.dp_street }}, {{ dp.dp_house }}</div>
      </div>

      <div class="fs-6">({{ dp.dp_counts }})</div>
    </div>
  </div>
  </router-link>
</template>

<style scoped>

a {
  text-decoration: none !important;
}

.card\:hover:hover {
  background: #0d6efd;
}

.card\:hover:hover,
.card\:hover:hover a,
.text-bg-primary a {
  color: white;
}

.card-body {
  padding: 8px !important;
}

</style>