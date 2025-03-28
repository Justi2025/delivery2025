
<script>
import {servicePolzovatelei} from "../../api/index.js";
import QTableExtended from "../../komponenti/QTableExtended.vue";
import Pagination from "../../komponenti/Pagination.vue";
import DataOrFallback from "../../komponenti/DataOrFallback.vue";
import {getStatusById, orderStatusTranslate} from "../../utils/zakazi/statusi_zakaza.js";
import shrink_fullname from "../../utils/shrink_fullname.js";
import OptsiiSdelok from "../../api/sdelki/OptsiiSdelok.js";
import KolichestvoBonusovProsto from "../../api/bonusi/KolichestvoBonusovProsto.js";
import {bonusesService} from "../../api/bonusi/ServisBonusov.js";

export default {
  name: "BonusHistory",
  components: {DataOrFallback, Pagination, QTableExtended},

  data: () => ({
    list: [],
    pagination: {},

    cols: {
      order_id: 'Заказ',
      operation_type: 'Операция',
      bonus_amount: 'Количество',
      employee_name: 'Сотрудник',
      created_at: 'Дата',
      comment: 'Комментарий',
    },

    bonus_quantity: 0,
    bonus_operation_comment: '',

  }),

  async mounted() {
    await this.loadBonusHistory();
  },

  computed: {
    withdraw_bonuses_button_disabled() {
      return this.current_bonuses <= 0;
    },

    current_bonuses() {
      return this.list.length > 0 ? this.list[0]?.customer_bonuses : 0
    },

  },

  methods: {
    shrink_fullname,
    orderStatusTranslate,
    getStatusById,


    operation_type_css(bonus_amount) {
      if (bonus_amount > 0) return 'success';
      return 'danger';
    },

    async loadBonusHistory() {
      const opts = new OptsiiSdelok();
      opts.query = this.$route.query;

      const {data} = await servicePolzovatelei().poluchitIstoriuBonusov(this.$route.params.id, opts);
      this.list = data.data;
      this.pagination = data;
    },

    async addBonuses() {
      const bonus = new KolichestvoBonusovProsto(
          this.$route.params.id,
          this.bonus_quantity,
          this.bonus_operation_comment
      );

      // console.log(operation);

      bonusesService()
          .addBonuses(bonus)
          .then(res => res.data)
          .then(bonus => {
            this.list = [bonus, ...this.list];
            this.bonus_quantity = 0;

            alert('Бонусы успешно начислены');
          })
          .catch(e => {
            alert('Ошибка при начислении бонусов');
            console.error(e);
          });
    },

    async withdrawBonuses() {
      const bonus = new KolichestvoBonusovProsto(
          this.$route.params.id,
          -this.bonus_quantity,
          this.bonus_operation_comment
      );

      bonusesService()
          .withdrawBonuses(bonus)
          .then(res => res.data)
          .then(bonus => {
            this.list = [bonus, ...this.list];
            this.bonus_quantity = 0;

            alert('Бонусы успешно списаны');
          })
          .catch(e => {
            alert('Ошибка при списании бонусов');
            console.error(e);
          });
    }
  }
}
</script>

<template>

  <div class="row d-flex align-items-center">

    <div class="col-12 mb-2" v-if="current_bonuses > 0">
      <div class="alert alert-warning">Баланс: {{ current_bonuses }} бонусов</div>
    </div>

    <div class="col" v-if="is_admin_or_manager">
      <div class="bonuses-updater mb-3">
        <div class="row g-2">
          <div class="col">
            <label for="bonuses_sum" class="form-label">Количество</label>
            <input type="number" class="form-control form-control-sm" min="0" v-model="bonus_quantity" id="bonuses_sum">
          </div>
          <div class="col-md-auto col-md-8">
            <label class="form-label" for="bonus_operation_comment">Комментарий</label>
            <input type="text" class="form-control form-control-sm" v-model="bonus_operation_comment" id="bonus_operation_comment">
          </div>
          <div class="col-md-auto d-flex align-items-end">
            <div>
              <button class="btn btn-sm btn-outline-success me-2" @click="addBonuses">
                Начислить
              </button>
              <button class="btn btn-sm btn-outline-danger" @click="withdrawBonuses"
                      :disabled="withdraw_bonuses_button_disabled">
                Списать
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

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
        <!-- <td>{{ shrink_fullname(item.customer_name) }}</td> -->
        <td>{{ shrink_fullname(item.employee_name) }}</td>
        <td>{{ dt_format2(item.created_at) }}</td>
        <td>{{ item.comment || '-' }}</td>
      </tr>
    </q-table-extended>

    <div class="row">
      <pagination :pagination="pagination"/>
    </div>
    <template #fallback>
      <h4 class="h4 mt-4">Нет бонусных начислений</h4>
    </template>
  </data-or-fallback>
</template>

<style scoped>

</style>