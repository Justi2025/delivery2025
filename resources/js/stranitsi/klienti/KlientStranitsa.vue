/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>
    <zagolovok-stranitc :caption="getPageTitle">
      <router-link :to="url('customers')" class="btn btn-sm btn-outline-warning">Назад</router-link>
    </zagolovok-stranitc>


    <ul class="nav nav-tabs">
      <li class="nav-item">
        <router-link :to="profileUrl" class="nav-link" :class="{ 'active': isActive(profileUrl) }" aria-current="page">
          Общие сведения
        </router-link>
      </li>
      <li class="nav-item">
        <router-link :to="currentOrderUrl" class="nav-link" :class="{ 'active': isActive(currentOrderUrl) }">
          Текущие заказы
        </router-link>
      </li>
      <li class="nav-item">
        <router-link :to="completedOrdersUrl" class="nav-link" :class="{ 'active': isActive(completedOrdersUrl) }">
          Выполненые заказы
        </router-link>
      </li>
      <li class="nav-item">
        <router-link :to="bonusHistoryUrl" class="nav-link" :class="{ 'active': isActive(bonusHistoryUrl) }">
          История бонусов
        </router-link>
      </li>
    </ul>


    <div class="mt-3">
      <router-view />
    </div>

  </obertka-adminki>
</template>

<script>
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";

export default {
  name: "CustomerPage",
  components: {
     ZagolovokStranitc, ObertkaAdminki
  },

  data() {
    return {
      profilePhoto: null,
      customer: null,

      profileUrl: '',
      currentOrderUrl: '',
      completedOrdersUrl: '',
      bonusHistoryUrl: ''
    }
  },

  async mounted() {
    const id = +this.$route.params.id;

    this.profileUrl = this.url('customers.profile', { id });
    this.currentOrderUrl = this.url('customers.current_orders', { id });
    this.completedOrdersUrl = this.url('customers.completed-orders', { id });
    this.bonusHistoryUrl = this.url('customers.bonus-history', { id });
  },

  computed: {
    getPageTitle() {
      return `${this.$route.meta.caption}`;
    }
  },

  methods: {

    isActive(route) {
      return this.$route.path === route;
    },

  }
}
</script>

<style scoped>

</style>
