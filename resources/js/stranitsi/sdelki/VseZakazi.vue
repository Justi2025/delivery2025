
<template>
  <obertka-adminki>

    <template v-if="isFormOpen">
      <order-creation-form :error="error_message" @on-form-close="toggleForm" @on-form-data="onFormData"
                           @on-search-input-clear="onSearchInputClear"/>
    </template>

    <template v-else>
      <zagolovok-stranitc :caption="$route.meta.caption">
        <button class="btn btn-sm btn-warning d-flex" @click="toggleForm">
          <i class="bi bi-plus-lg me-1"></i>Добавить заказ
        </button>
      </zagolovok-stranitc>

      <div class="mx-1">

        <q-loading :status="zagrujaetsia_ili_net">
          <orders-filter :filter-id="page_id" @on-filter="onFilter" @on-filter-clear="ochistitFiltr"
                         @on-orders-sort="onOrdersSort" v-if="!is_customer"/>

          <simplified-orders-filter :filter-id="page_id" @on-filter="onFilter" @on-filter-clear="ochistitFiltr"
                                    @on-orders-sort="onOrdersSort" v-else/>

          <div class="row g-2 align-items-center mb-2">
            <div class="col">
              <input type="button" value="Фильтры" class="btn btn-sm mb-3"
                     :class="{'btn-outline-warning': filter_empty, 'btn-warning': !filter_empty}"
                     data-bs-target="#orderFiltersModal" data-bs-toggle="modal"
              />
            </div>
            <div class="col mt-0 d-flex justify-content-end visually-hidden">
              &nbsp;
            </div>
          </div>

          <template v-if="orders.length > 0">
            <div class="row g-3" v-if="view_type === 'list'">
              <orders-table :cols="cols" :orders="orders" v-if="!is_customer" :pagination="pagination" />
              <orders-table-simplified :cols="customerColumns(cols)" :orders="orders" v-else/>
            </div>

            <div class="row mt-2">
              <pagination :pagination="pagination"/>
            </div>

          </template>
          <h4 class="h4" v-else>
            Заказов нет, но их всегда можно создать :)
          </h4>

        </q-loading>
      </div>

    </template>
  </obertka-adminki>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import OrderCreationForm from "./FormaSozdaniaZakaza.vue";
import QTable from "../../komponenti/QTable.vue";
import Pagination from "../../komponenti/Pagination.vue";
import QTableExtended from "../../komponenti/QTableExtended.vue";
import {servicSdelok} from "../../api/sdelki/SdelkiServis.js";
import {defaultType, getStatusById} from "../../utils/zakazi/statusi_zakaza.js";
import ModalDialog from "../../komponenti/ModalDialog.vue";
import Multiselect from "../../komponenti/Multiselect.vue";
import optsciiSortirovkiSdelok from './obschie/optsciiSortirovkiSdelok.js';
import {mapGetters, mapMutations} from "vuex";
import OrdersTable from "./komponenti/TablitsaZakazov.vue";
import OrdersFilter from "./komponenti/FiltrZakazov.vue";
import OrderPreviewCard from "./komponenti/PreviewKartochkiZakaza.vue";
import ViewTypeSwitcher from "./komponenti/PerekluchatelVida.vue";
import OrdersTableX from "./komponenti/TablitsaZakazovX.vue";
import OrdersTableSimplified from "./komponenti/UpraschennaiaTablitsaZakazov.vue";
import SimplifiedOrdersFilter from "./komponenti/SimplifiedOrdersFilter.vue";
import QLoading from "../../komponenti/QLoading.vue";

export default {
  name: "OrdersPage",
  components: {
    QLoading,
    SimplifiedOrdersFilter,
    OrdersTableSimplified,
    OrdersTableX,
    ViewTypeSwitcher,
    OrderPreviewCard,
    OrdersFilter,
    OrdersTable,
    Multiselect,
    ModalDialog, QTableExtended, Pagination, QTable, OrderCreationForm, ObertkaAdminki, ZagolovokStranitc
  },

  data: () => ({
    page_id: 'all_orders',
    list: [],
    isFormOpen: false,
    error_message: null,

    cols: {
      order_no: 'Номер',
      order_status: 'Статус',
      customer: 'Клиент',
      shipment_from: 'Откуда',
      shipment_to: 'Куда',
      amount_to_pay: 'К оплате',
      price: 'Цена',
      debt: 'Долг',
      created_at: 'Создан',
    },
    orders: [],
    pagination: {},

    zagrujaetsia_ili_net: false,
  }),

  async mounted() {
    await this.zagruzit_zakazi();
  },

  computed: {

    ...mapGetters('ordersFilterStore', ['getFiltersAndSortOptions', 'isFilterEmpty']),
    ...mapGetters('pageSettingsStore', ['getPageSettings']),

    filter_opts() {
      return this.getFiltersAndSortOptions(this.page_id);
    },

    filter_empty() {
      return this.isFilterEmpty(this.page_id);
    },

    page_settings() {
      return this.getPageSettings(this.page_id);
    },

    view_type: {
      get() {
        return this.page_settings.view_type;
      },
      set(value) {
        this.setSettings({view_type: value});
      }

    },

    order_status: () => (id) => {
      return getStatusById(id) || defaultType;
    },

    orderSortOptions() {
      return optsciiSortirovkiSdelok;
    },

  },

  methods: {

    ...mapMutations('pageSettingsStore', ['SET_SETTINGS_FOR_PAGE']),


    setSettings(settings) {
      this.SET_SETTINGS_FOR_PAGE({page: this.page_id, settings});
    },


    customerColumns(cols) {
      return {
        order_no: 'Номер',
        order_status: 'Статус',
        shipment_from: 'Откуда',
      };
    },

    toggleForm() {
      this.isFormOpen = !this.isFormOpen;
    },

    async onFormData(dostavka_dannie) {
      try {
        this.zagrujaetsia_ili_net = true;
        const {data} = await servicSdelok().sozdatDostavku(dostavka_dannie);
        this.orders = [data, ...this.orders];
        this.toggleForm();
      } catch (e) {
        this.error_message = e?.response?.data;
      } finally {
        this.zagrujaetsia_ili_net = false;
      }
    },

    onSearchInputClear() {
      this.error_message = null;
    },

    async zagruzit_zakazi() {
      try {
        const {data} = await servicSdelok().poluchitVseSdelki(this.filter_opts);

        this.orders = data.data.map(order => ({
          ...order,
          amount_to_pay: order?.amount_to_pay,
          price: order?.price,
          client_debt: order?.client_debt
        }));
        this.pagination = data;
      } catch (e) {
        if (404 === e?.response?.status) {
          this.orders = [];
          this.pagination = {};
        }
      }
    },

    async onFilter() {
      this.$router.push({path: this.$route.path, query: {}});
      await this.zagruzit_zakazi();
    },

    async ochistitFiltr() {
      await this.zagruzit_zakazi();
    },

    async onOrdersSort() {
      await this.zagruzit_zakazi();
    },

  },
}
</script>

<style scoped>

</style>