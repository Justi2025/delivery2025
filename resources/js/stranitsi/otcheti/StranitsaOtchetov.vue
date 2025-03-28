/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>
    <zagolovok-stranitc :caption="caption">
      <select v-model="selected_report" class="form-select form-select-sm bg-secondary-subtle" @change="onReportSelect"
              v-if="is_admin()">
        <option selected value="">Выберите тип отчета</option>
        <option :value="SelectedReportType.ByDay">Приход по дням</option>
        <option :value="SelectedReportType.ByDayAndPickupPoints">Приход по дням и офисам</option>
        <option :value="SelectedReportType.DebtsByCustomers">Долги клиентов</option>
      </select>
    </zagolovok-stranitc>


    <div class="row g-2 align-items-center my-3" v-if="is_admin()">
      <div class="col-auto d-flex">
        <input type="date" class="form-control form-control-sm" title="Отчет за день" v-model="report_day"
               @change="onDayChangedDebounced"/>
      </div>
      <div class="col-auto d-flex justify-content-end">
        <select class="form-select form-select-sm" v-model="selected_office" @change="onOfficeSelected" v-if="isByDayAndPickupPoints">
          <option value="" selected disabled>Выберите офис</option>
          <option value="0">Все офисы</option>
          <option :value="address.id" v-for="address in addresses_ab">{{ address.value }}</option>
        </select>
      </div>
    </div>

    <div v-if="isByDay">
      <q-table :cols="by_day_cols" :rows="list">
        <template #item="{item}">
          <td>{{ dt_format_ddmmyyy(item.day) }}</td>
          <td>{{ money(item.total_to_pay) }}</td>
          <td>{{ money(item.total_paid) }}</td>
          <td>{{ money(item.total_debt) }}</td>
          <td>{{ money(item.total_cash) }}</td>
          <td>{{ money(item.total_cashless) }}</td>
          <td>{{ money(item.total_bonus) }}</td>
        </template>
      </q-table>

      <pagination :pagination="pagination" v-if="pagination.total >= 15"/>
    </div>

    <div v-else-if="isByDayAndPickupPoints">

      <div class="row g-3" v-if="is_operator">
        <div class="col-md-4" v-for="(item, counter) in formatForCardView" :key="counter">
          <grouped-payments-card :item="item"/>
        </div>
      </div>


      <q-table :cols="cols" :rows="list" v-else>
        <template #item="{item}">
          <td>{{ dt_format_ddmmyyy(item.day) }}</td>
          <td>{{ item.office || '-' }}</td>
          <td>{{ money(item.total_to_pay) }}</td>
          <td>{{ money(item.total_paid) }}</td>
          <td>{{ money(item.total_debt) }}</td>
          <td>{{ money(item.total_cash) }}</td>
          <td>{{ money(item.total_cashless) }}</td>
          <td>{{ money(item.total_bonus) }}</td>
        </template>
      </q-table>

      <pagination :pagination="pagination" v-if="pagination.total >= 15"/>
    </div>

    <div v-else-if="isDebtsByCustomers">

      <q-table :cols="debt_cols" :rows="list">
        <template #item="{item}">
          <td>
            <router-link :to="url('dostavka.view', { id: item?.order_id })" v-if="item?.order_id">
              #{{ format_order_no(item?.order_id) }}
            </router-link>
          </td>
          <td>{{ item?.full_name }}</td>
          <td>{{ money(item?.total_price) }}</td>
          <td>{{ money(item?.total_paid) }}</td>
          <td>{{ money(item?.debt) }}</td>
        </template>
      </q-table>

      <pagination :pagination="pagination" v-if="pagination.total >= 15"/>
    </div>


  </obertka-adminki>
</template>

<script>
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import QTable from "../../komponenti/QTable.vue";
import {mapMutations, mapState} from "vuex";
import {reportsService} from "../../api/otcheti/ServisOtchetov.js";
import OrdersTable from "../sdelki/komponenti/TablitsaZakazov.vue";
import Pagination from "../../komponenti/Pagination.vue";
import ViewTypeSwitcher from "../sdelki/komponenti/PerekluchatelVida.vue";
import GroupedPaymentsCard from "./GroupedPaymentsCard.vue";
import {objiect_pustoi} from "../../utils/objiect_pustoi.js";
import VibranniTipOtecheta from "./VibranniTipOtecheta.js";
import debounce from "../../utils/debounce.js";
import FiltrOtchetovPo from "../../api/otcheti/FiltrOtchetovPo.js";


export default {
  name: "ReportsPage",
  components: {
    GroupedPaymentsCard,
    ViewTypeSwitcher,
    Pagination,
    OrdersTable, QTable, ZagolovokStranitc, ObertkaAdminki
  },

  data: () => ({
    list: [],
    pagination: {},
    grouped: [],

    by_day_cols: {
      day: 'День',
      total_to_pay: 'Заказов выдано на',
      total_paid: 'Получено',
      total_debt: 'Долг',
      total_cash: 'Наличными',
      total_cashless: 'Безналичными',
      total_bonus: 'Бонусами'
    },

    cols: {
      day: 'День',
      office: 'Офис',
      total_to_pay: 'Заказов выдано на',
      total_paid: 'Получено',
      total_debt: 'Долг',
      total_cash: 'Наличными',
      total_cashless: 'Безналичными',
      total_bonus: 'Бонусами'
    },

    debt_cols: {
      order_id: 'Номер заказа',
      customer: 'ФИО',
      total_price: 'Стоимость',
      total_paid: 'Уплачено',
      debt: 'Долг'
    },

    view_type: 'list',

    onDayChangedDebounced: null,
  }),

  async mounted() {

    if (this.is_operator) {
      this.selected_report = VibranniTipOtecheta.ByDayAndPickupPoints;
    }

    await this.onReportSelect();

    this.onDayChangedDebounced = debounce(this.onDayChange, 300);
  },

  computed: {

    ...mapState('reportsPageStore', ['selected_report_type', 'selected_office_id', 'selected_day']),
    ...mapState(['addresses']),

    addresses_ab() {
      return this.addresses.filter(a => a.country === 'ab');
    },

    SelectedReportType() {
      return VibranniTipOtecheta;
    },


    caption() {
      if (this.is_operator) {
        const item = this.list[0];
        const day = this.dt_format_ddmmyyy(item?.day);
        return `${this.$route.meta.caption} за ${day}`;
      }
      return this.$route.meta.caption;
    },

    isCardViewType() {
      return this.view_type === 'card';
    },

    selected_report: {
      get() {
        return this.selected_report_type;
      },
      set(value) {
        this.SET_SELECTED_REPORT_TYPE(value);
      }
    },

    selected_office: {
      get() {
        return this.selected_office_id;
      },
      set(value) {
        this.SET_SELECTED_OFFICE_ID(value);
      }
    },

    report_day: {
      get() {
        return this.selected_day;
      },
      set(value) {
        this.SET_SELECTED_DAY(value);
      }
    },

    isByDay() {
      return this.selected_report === VibranniTipOtecheta.ByDay;
    },

    isByDayAndPickupPoints() {
      return this.selected_report === VibranniTipOtecheta.ByDayAndPickupPoints;
    },

    isDebtsByCustomers() {
      return this.selected_report === VibranniTipOtecheta.DebtsByCustomers;
    },

    formatForCardView() {
      const acc = [];

      const item = this.list[0];
      const total_to_pay = {clazz: 'primary', label: this.cols.total_to_pay, amount: item?.total_to_pay || 0};
      const total_paid = {clazz: 'success', label: this.cols.total_paid, amount: item?.total_pai || 0};
      const total_debt = {clazz: 'danger', label: this.cols.total_debt, amount: item?.total_debt || 0};
      const total_cash = {clazz: 'warning', label: this.cols.total_cash, amount: item?.total_cash || 0};
      const total_cashless = {clazz: 'success', label: this.cols.total_cashless, amount: item?.total_cashless || 0};
      const total_bonus = {clazz: 'primary', label: this.cols.total_bonus, amount: item?.total_bonus || 0};

      acc.push(total_to_pay);
      acc.push(total_paid);
      acc.push(total_debt);
      acc.push(total_cash);
      acc.push(total_cashless);
      acc.push(total_bonus);

      return acc;
    }
  },

  methods: {
    isEmpty: objiect_pustoi,
    ...mapMutations('reportsPageStore', ['SET_SELECTED_REPORT_TYPE', 'SET_SELECTED_OFFICE_ID', 'SET_SELECTED_DAY']),


    /**
     *
     * @param filter {FiltrOtchetovPo}
     * @return {Promise<void>}
     */
    async getPaymentsByDayAndPickupPoints(filter = null) {
      const {data} = await reportsService().platejiPoDniamIOfisam(this.$route.query, filter);
      this.list = data.data;
      this.pagination = data;
    },

    async getPaymentsByDay(filter) {
      const {data} = await reportsService().poDniam(this.$route.query, filter);
      this.list = data.data;
      this.pagination = data;
    },

    async getDebtsByCustomers(day = null) {
      const filter = !day ? {} : {day};
      const {data} = await reportsService().dolgiKlientov(this.$route.query, filter);
      this.list = data.data;
      this.pagination = data;
    },

    async onReportSelect() {

      const filter = new FiltrOtchetovPo();
      filter.day = this.report_day;
      filter.office = this.selected_office;

      switch (this.selected_report) {
        case VibranniTipOtecheta.ByDay:
          await this.getPaymentsByDay(filter);
          break;

        case VibranniTipOtecheta.ByDayAndPickupPoints:
          await this.getPaymentsByDayAndPickupPoints(filter);
          break;

        case VibranniTipOtecheta.DebtsByCustomers:
          await this.getDebtsByCustomers();
          break;
      }
    },


    async onDayChange() {
      try {
        await this.onReportSelect();
      } catch (e) {
        alert(e?.response?.data?.message);
      }
    },


    async onOfficeSelected() {
      try {
        await this.onReportSelect();
      } catch (e) {
        alert(e?.response?.data?.message);
      }
    }

  },


}
</script>

<style scoped>

</style>
