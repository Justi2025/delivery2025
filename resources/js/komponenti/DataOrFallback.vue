/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<script>
export default {
  name: "DataOrFallback",
  props: {
    data: {
      type: Array,
      required: true
    }
  }
}
</script>

<template>
  <slot v-if="data?.length > 0"/>
  <slot name="fallback" v-else/>
</template>

<style scoped>

</style>