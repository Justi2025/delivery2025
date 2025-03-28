/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>
    <zagolovok-stranitc caption="Доступ запрещен"/>
    <p>Доступ запрещен!</p>
  </obertka-adminki>
</template>

<script>
import ObertkaAdminki from "../komponenti/ObertkaAdminki.vue";
import ZagolovokStranitc from "../komponenti/ZagolovokStranitc.vue";

export default {
    name: "Forbidden",
    components: {ZagolovokStranitc, ObertkaAdminki}
}
</script>

<style scoped>

</style>
