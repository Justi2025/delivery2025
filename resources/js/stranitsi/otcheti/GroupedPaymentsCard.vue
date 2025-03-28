
<script>
export default {
  name: "GroupedPaymentsCard",

  props: {
    item: {
      type: Object,
      required: true
    }
  }
}
</script>

<template>
  <div class="card h-100 shadow" :class="'bg-' + item?.clazz">
    <div class="card-body">
      <h5 class="h5" style="font-weight: lighter">{{ item?.label }}</h5>
      <h3 class="h3 pt-2">{{ money(item?.amount) }}</h3>
    </div>
  </div>
</template>

<style scoped>
.card {
  color: white;
}
</style>