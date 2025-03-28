
<template>
  <router-view :key="$route.fullPath"></router-view>
</template>

<script>
import DashboardSvgs from "./komponenti/IkonkiPrilojenia.vue";
import AppHeader from "./komponenti/ZagolovokStrPrilojenia.vue";
import AppSidebar from "./komponenti/LevoeMenu.vue";
import {mapActions, mapState} from "vuex";

export default {
  name: "App",
  components: {AppSidebar, AppHeader, DashboardSvgs},
  async mounted() {
    if (this.user && this.isUserAuthenticated) {
      await this.initDarkMode();

      if (!this.is_customer) {
        await this.loadCouriers();
      }
      await this.loadAddresses();
      await this.loadCities();
      await this.getProfileData();
      await this.loadCompanies();
    }
  },

  computed: {
    ...mapState('authStore', ['user', 'isUserAuthenticated']),
  },

  methods: {
    ...mapActions(['loadCities', 'loadCouriers', 'loadAddresses', 'getProfileData', 'loadCompanies']),
    ...mapActions('appSettingsStore', ['getAndSetUnreadPostsCount', 'initDarkMode']),
  },
}
</script>

<style scoped>
.App {
  height: calc(100% - 55px);
}

.App > .row {
  height: 100%
}

@media screen and (max-width: 768px) {
  .App {
    height: auto;
  }

}
</style>
