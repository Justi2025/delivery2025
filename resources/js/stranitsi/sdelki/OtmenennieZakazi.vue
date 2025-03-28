/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>
    <zagolovok-stranitc :caption="$route.meta.caption"/>

    <div class="mx-1">

      <orders-filter :filter-id="page_id" @on-filter="onFilter" @on-filter-clear="onFilterClear" @on-orders-sort="onOrdersSort"  />

      <div class="row g-2">
        <div class="col-auto">
          <input type="button" value="Фильтры и cортировка" class="btn btn-sm mb-3"
                 :class="{'btn-outline-warning': filter_empty, 'btn-warning': !filter_empty}"
                 data-bs-target="#orderFiltersModal" data-bs-toggle="modal"
          />
        </div>
      </div>

      <orders-table :cols="cols" :orders="orders" :pagination="pagination" v-if="orders.length > 0" />
    </div>
  </obertka-adminki>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import {servicSdelok} from "../../api/sdelki/SdelkiServis.js";
import OptsiiSdelok from "../../api/sdelki/OptsiiSdelok.js";
import {OrderStatus} from "../../utils/zakazi/statusi_zakaza.js";
import OrdersTable from "./komponenti/TablitsaZakazov.vue";
import OrdersFilter from "./komponenti/FiltrZakazov.vue";
import {mapGetters} from "vuex";

export default {
  name: "CanceledOrdersPage",
  components: {OrdersFilter, OrdersTable, ObertkaAdminki, ZagolovokStranitc},

  data: () => ({
    page_id: 'cancelled',

    cols: {
      order_no: 'Номер',
      order_status: 'Статус',
      customer: 'Клиент',
      shipment_from: 'Откуда',
      shipment_to: 'Куда',
      courier: 'Курьер',
      customer_city: 'Город',
      amount_to_pay: 'К оплате',
      created_at: 'Создан',
    },

    orders: [],
    pagination: {}
  }),

  async mounted() {
    await this.loadOrders();
  },

  computed: {

    ...mapGetters('ordersFilterStore', ['getFiltersAndSortOptions', 'isFilterEmpty']),

    filter_opts() {
      return this.getFiltersAndSortOptions(this.page_id);
    },

    filter_empty() {
      return this.isFilterEmpty(this.page_id);
    },
  },

  methods: {
    /**
     *
     * @return {Promise<void>}
     */
    async loadOrders() {

      try {
        const {data} = await servicSdelok().poluchitOtmenennieSdelki(this.filter_opts);
        this.orders = data.data;
        this.pagination = data;
      }
      catch (e) {
        const status = e?.response?.status || 0;

        if (404 === status) {
          alert('Нет заказов по выбранным критериям');
        } else {
          this.error_message = e?.response?.data?.message;
        }
      }
    },


    async onFilter() {
      this.$router.push({path: this.$route.path, query: {}});
      await this.loadOrders();
    },

    async onFilterClear() {
      await this.loadOrders();
    },

    async onOrdersSort() {
      await this.loadOrders();
    },
  },
}
</script>

<style scoped>

</style>