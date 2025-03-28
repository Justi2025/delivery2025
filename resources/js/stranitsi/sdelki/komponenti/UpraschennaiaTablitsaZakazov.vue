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
  name: "OrdersTableSimplified",
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
    shrink_fullname,
    orderStatusTranslate,
  },
}
</script>

<template>
  <div class="mx-1">
    <q-table-extended :cols="cols">
      <tr v-for="(order, counter) in orders" :data-item-id="order.id" class="cursor-pointer" :key="order.id" @click="openOrderCardOnRowClick">
        <th scope="row">{{ ++counter }}</th>
        <td>
          <router-link :to="url('dostavka.view', { id: order.id })">
            #{{ format_order_no(order.id) }}
          </router-link>
        </td>
        <td>
            <span :class="'badge bg-' + order_status(order?.order_status).clazz">
            {{ orderStatusTranslate(order.order_status) }}
            </span>
        </td>
        <td>
          {{ order.shipment_from_company }},
          {{ order.shipment_from_city }},
          {{ order.shipment_from_address }}
        </td>
      </tr>
    </q-table-extended>

  </div>
</template>

<style scoped>

</style>