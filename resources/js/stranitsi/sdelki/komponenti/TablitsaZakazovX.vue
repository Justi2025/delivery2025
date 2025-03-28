/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<script>
import QTableExtended from "../../../komponenti/QTableExtended.vue";
import Pagination from "../../../komponenti/Pagination.vue";
import {getStatusById, orderStatusTranslate} from "../../../utils/zakazi/statusi_zakaza.js";
import ModalDialog from "../../../komponenti/ModalDialog.vue";
import Multiselect from "../../../komponenti/Multiselect.vue";
import {defaultType} from "../../../utils/reviewTypes.js";
import shrink_fullname from "../../../utils/shrink_fullname.js";

export default {
  name: "OrdersTableX",
  components: {Multiselect, ModalDialog, Pagination, QTableExtended},

  props: {
    cols: {
      type: Object,
      required: true,
    },
    orders: {
      type: Array,
      required: true
    },
    pagination: {
      type: Object,
      required: false,
    },
    filtersEnabled: {
      type: Boolean,
      required: false,
      default: true
    }
  },

  data: () => ({

  }),

  async mounted() {
  },

  computed: {
    order_status: () => (id) => {
      return getStatusById(id) || defaultType;
    },
  },

  methods: {
    orderStatusTranslate,
    shrink_fullname,
  },
}
</script>

<template>
  <div class="mx-1">

    <q-table-extended :cols="cols">
      <tr v-for="(order, counter) in orders" :data-item-id="order.id" class="cursor-pointer" :key="order.id">
        <th scope="row">{{ ++counter }}</th>
        <td>
          <router-link :to="route_url('dostavka.view', { id: order.id }).fullPath">
            #{{ format_order_no(order.id) }}
          </router-link>
        </td>
        <td>
            <span :class="'badge bg-' + order_status(order?.order_status).clazz">
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
  </div>
</template>

<style scoped>

</style>