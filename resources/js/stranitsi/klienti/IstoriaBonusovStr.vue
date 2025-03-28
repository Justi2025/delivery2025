/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>
    <zagolovok-stranitc :caption="$route.meta.caption" />


    <data-or-fallback :data="list">
      <q-table-extended :cols="cols">
        <tr v-for="(item, counter) in list" :data-item-id="item.id" class="cursor-pointer" :key="item.id">
          <th scope="row">{{ ++counter }}</th>
          <td>
            <router-link :to="url('dostavka.view', { id: item.order_id })" v-if="item.order_id">
              #{{ format_order_no(item.order_id) }}
            </router-link>
            <span v-else>-</span>
          </td>
          <td>
          <span :class="`badge bg-${operation_type_css(item.bonus_amount)} d-inline-block`">
            {{ item.bonus_amount > 0 ? 'Начислено' : 'Списано' }}
          </span>
          </td>
          <td>{{ item.bonus_amount }}</td>
          <td>{{ dt_format2(item.created_at) }}</td>
        </tr>
      </q-table-extended>

      <div class="row">
        <pagination :pagination="pagination"/>
      </div>
      <template #fallback>
        <h4 class="h4 mt-4">Нет бонусных начислений</h4>
      </template>
    </data-or-fallback>

  </obertka-adminki>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import Pagination from "../../komponenti/Pagination.vue";
import DataOrFallback from "../../komponenti/DataOrFallback.vue";
import QTableExtended from "../../komponenti/QTableExtended.vue";
import OptsiiSdelok from "../../api/sdelki/OptsiiSdelok.js";
import {servicePolzovatelei} from "../../api/index.js";
import {mapGetters} from "vuex";

export default {
  name: "BonusHistoryPage",
  components: {QTableExtended, DataOrFallback, Pagination, ObertkaAdminki, ZagolovokStranitc},

  data: () => ({
    list: [],
    pagination: {},

    cols: {
      order_id: 'Заказ',
      operation_type: 'Операция',
      bonus_amount: 'Количество',
      created_at: 'Дата',
    },
  }),

  async mounted() {
    await this.loadBonusHistory();
  },

  computed: {
    ...mapGetters('authStore', ['user']),

    customer() {
      return this.user;
    },
  },

  methods: {

    operation_type_css(bonus_amount) {
      if (bonus_amount > 0) return 'success';
      return 'danger';
    },

    async loadBonusHistory() {
      const opts = new OptsiiSdelok();
      opts.query = this.$route.query;

      const {data} = await servicePolzovatelei().poluchitIstoriuBonusov(this.customer?.user_id, opts);
      this.list = data.data;
      this.pagination = data;
    },
  },
}
</script>

<style scoped>

</style>