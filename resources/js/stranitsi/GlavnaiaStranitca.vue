/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki :has-content="hasContent" :is-loading="isLoading">

    <template v-if="!isFormOpen">
      <zagolovok-stranitc :caption="this.$route.meta.caption">
        <button v-if="is_admin()" class="btn btn-sm btn-warning d-flex" @click="openForm">
          <i class="bi bi-plus-lg me-1"></i>Создать заявку
        </button>
      </zagolovok-stranitc>
      <div class="row">
        <div v-for="item in items" class="col-sm-12 col-md-6 col-lg-4 mb-3">
          <div>Card</div>
        </div>
      </div>
    </template>

    <template v-else>
      <zagolovok-stranitc :caption="captionValue">
        <button class="btn btn-sm btn-warning" @click="closeForm">Закрыть</button>
      </zagolovok-stranitc>
      <div class="mx-1">
        <deal-creation-form />
      </div>
    </template>

  </obertka-adminki>
</template>

<script>

import ZagolovokStranitc from "../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../komponenti/ObertkaAdminki.vue";
import DealCreationForm from "./otpravka/DealCreationForm.vue";

export default {
  name: "Dashboard",
  components: {DealCreationForm, ObertkaAdminki, ZagolovokStranitc},
  data() {
    return {
      isFormOpen: false,
      items: [],
      isEditing: false,
      lecture: {},
      isLoading: false,
      hasContent: false
    }
  },
  computed: {
    captionValue() {
      return this.isEditing ? 'Обновить' : 'Новая сделка';
    }
  },
  async mounted() {
    try {

      this.isLoading = true;
      const {data} = {data: []};

      this.items = data;
      this.hasContent = true;
    } catch (e) {
      this.hasContent = false;
      console.log(e)
    } finally {
      this.isLoading = false;
    }


  },
  methods: {

    closeForm() {

      this.isFormOpen = false;
    },

    openForm() {
      this.isFormOpen = true;
      this.isEditing = false;
    },
  }
}
</script>

<style scoped>

</style>
