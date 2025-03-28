/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<script>
import OrdersTable from "../sdelki/komponenti/TablitsaZakazov.vue";
import {servicePolzovatelei} from "../../api/index.js";
import DataOrFallback from "../../komponenti/DataOrFallback.vue";
import OptsiiSdelok from "../../api/sdelki/OptsiiSdelok.js";
import QTableExtended from "../../komponenti/QTableExtended.vue";
import BarcodeImage from "../sdelki/komponenti/ShtrikhKodIzobrajenie.vue";
import Pagination from "../../komponenti/Pagination.vue";
import {getStatusById, orderStatusTranslate} from "../../utils/zakazi/statusi_zakaza.js";
import shrink_fullname from "../../utils/shrink_fullname.js";

export default {
  name: "CurrentOrders",
  components: {Pagination, BarcodeImage, QTableExtended, DataOrFallback, OrdersTable},

  data: () => ({
    orders: [],
    pagination: {},

    cols: {
      id: 'Номер',
      order_status: 'Статус',
      customer: 'Клиент',
      shipment_from: 'Откуда',
      shipment_to: 'Куда',
      /*customer_city: 'Город',*/
      created_at: 'Создан',
      // received_at: 'Получен',
      // issued_at: 'Выдан',
    },
  }),

  async mounted() {
    await this.loadOrders();
  },

  methods: {
    getStatusById,
    shrink_fullname,
    orderStatusTranslate,

    getOrderOptions() {
      const opts = new OptsiiSdelok();
      opts.query = this.$route.query;
      return opts;
    },

    async loadOrders() {
      const customer_id = this.$route.params.id;
      const {data} = await servicePolzovatelei().poluchitTekuscheiZakazi(customer_id, this.getOrderOptions());

      this.orders = data.data;
      this.pagination = data;
    },
  }
}
</script>

<template>
  <data-or-fallback :data="orders">
    <q-table-extended :cols="cols" @click="openOrderCard">
      <tr v-for="(order, counter) in orders" :data-item-id="order.id" class="cursor-pointer" :key="order.id">
        <th scope="row">{{ ++counter }}</th>
        <td>
          <router-link :to="route_url('dostavka.view', { id: order.id }).fullPath">
            #{{ format_order_no(order.id) }}
          </router-link>
        </td>
        <td>
            <span :class="'badge bg-' + getStatusById(order?.order_status).clazz">
            {{ orderStatusTranslate(order.order_status) }}
            </span>
        </td>
        <td>{{ shrink_fullname(order.customer) }}</td>
        <td>{{ order.shipment_from }}</td>
        <td>{{ order.shipment_to }}</td>
        <td>{{ dt_format2(order.created_at) }}</td>
      </tr>
    </q-table-extended>

    <div class="row">
      <pagination :pagination="pagination"/>
    </div>
    <template #fallback>
      <h4 class="h4">Нет текущих заказов</h4>
    </template>
  </data-or-fallback>
</template>

<style scoped>

</style>