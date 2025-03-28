/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>
    <zagolovok-stranitc :caption="$route.meta.caption" />

    {{ $route.path }}
  </obertka-adminki>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";

export default {
  name: "OrdersWaiting",
  components: {ObertkaAdminki, ZagolovokStranitc},

  data: () => ({
    list: [],
    isFormOpen: false,
  }),

  async mounted() {

  },

  methods: {
    toggleForm() {
      this.isFormOpen = !this.isFormOpen;
    }
  },
}
</script>

<style scoped>

</style>