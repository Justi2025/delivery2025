
<template>
  <template v-if="!isUserAuthenticated">
    <div class="container-fluid App">
      <div class="row pt-4">
        <h3>Запрашиваемая Вами страница не найдена</h3>
        <p>
          <router-link :to="url('login')">На страницу входа</router-link>
        </p>
      </div>
    </div>
  </template>
  <obertka-adminki v-else>
    <zagolovok-stranitc caption="Страница не найдена"/>
    <p>Запрашиваемая Вами страница не найдена (<b>{{ $route.path }}</b>)</p>
  </obertka-adminki>
</template>

<script>

import ObertkaAdminki from "../komponenti/ObertkaAdminki.vue";
import AppHeader from "../komponenti/ZagolovokStrPrilojenia.vue";
import {mapState} from "vuex";
import ZagolovokStranitc from "../komponenti/ZagolovokStranitc.vue";

export default {
  name: "NotFoundPage",
  components: {ZagolovokStranitc, AppHeader, ObertkaAdminki},

  computed: {
    ...mapState('authStore', ['isUserAuthenticated']),
  },
}
</script>

<style scoped>

</style>
