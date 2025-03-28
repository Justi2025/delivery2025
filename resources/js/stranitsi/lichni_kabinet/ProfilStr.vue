/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>
    <zagolovok-stranitc :caption="$route.meta.caption"/>

    <div class="mt-3">
      <nav-tabs id="settings_nav-tab">
        <nav-tab-link label="Профиль" name="profile" class="active" />
        <nav-tab-link label="Настройки" name="progress"/>
      </nav-tabs>
      <nav-tab-content id="settings_nav-tabContent">
        <nav-tab-pane class="active show" name="profile">
          <user-profile :user="user" v-if="user" />
        </nav-tab-pane>
        <nav-tab-pane name="progress">
          <profile-settings />
        </nav-tab-pane>
      </nav-tab-content>
    </div>
  </obertka-adminki>
</template>

<script>
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import NavTabLink from "../../komponenti/vkladki/NavTabLink.vue";
import NavTabPane from "../../komponenti/vkladki/NavTabPane.vue";
import NavTabs from "../../komponenti/vkladki/NavTabs.vue";
import NavTabContent from "../../komponenti/vkladki/NavTabContent.vue";
import UserProfile from "./komponenti/ProfilPolzovatelia.vue";
import UserProfileFragment from "../klienti/ProfilPolzovateliaStr.vue";
import {mapGetters} from "vuex";
import {authService, servicePolzovatelei} from "../../api/index.js";
import ProfileSettings from "./NastroikiProfilia.vue";


export default {
  name: "ProfilePage",
  components: {
    ProfileSettings,
    UserProfileFragment,
    UserProfile, NavTabContent, NavTabs, NavTabPane, NavTabLink, ZagolovokStranitc, ObertkaAdminki
  },

  data() {
    return {
      profilePhoto: null,
      user: null
    }
  },

  async mounted() {
    await this.loadUser();
  },

  computed: {
  },

  methods: {
    async loadUser() {
      // Todo: already defined action in global store
      const {data} = await authService().getUser();
      this.user = data;
    }
  }

}
</script>

<style scoped>

</style>
