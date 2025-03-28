/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>
    <zagolovok-stranitc :caption="$route.meta.caption">
      <div class="visually-hidden">
        <view-type-switcher v-model="view_type"/>
      </div>
    </zagolovok-stranitc>

    <div class="mx-1">

      <div class="row gy-3">
        <div class="col-md-12">
          <orders-searcher
              :store-id="page_id"
              :order-statuses="[OrderStatus.WaitingForCustomers]"
              @on-search-completed="onSearchCompleted" @on-error="onSearchError"
              @on-search-clear="onSearchClear" @on-loading="onSearchLoading"
           />
          <div class="text-danger">
            {{ search_error }}
          </div>
        </div>
        <div class="col-md-12" v-if="orders.length > 0">
          <div class="mx-1">

            <q-loading :status="is_loading">
              <div class="row g-3" v-if="view_type === 'list'">
                <q-table-extended :cols="cols" @click="openOrderCard">
                  <tr v-for="(order, counter) in orders" :data-item-id="order.id" class="cursor-pointer"
                      :key="order.id">
                    <th scope="row">{{ row_counter(counter, pagination.current_page, pagination.per_page) }}</th>
                    <td>
                      <router-link :to="route_url('dostavka.view', { id: order.id }).fullPath" :class="status_color(order)">
                        #{{ format_order_no(order.id) }}
                      </router-link>
                    </td>
                    <td>{{ order.warehouse_cells || '-' }}</td>
                    <td>
                      {{ shrink_fullname(order.customer, 2) }}
                    </td>
                    <td>
                      <span
                          :style="{backgroundColor: order?.shipment_from_color, color: order?.shipment_from_label_color}"
                          style="padding: 3px; font-size: .85rem" class="rounded-2">
                      {{ order.shipment_from }}
                      </span>
                    </td>
                    <!-- <td>{{ order.shipment_to }}</td> -->
                    <!-- <td>{{ order.customer_city || '-' }}</td>-->
                    <td>
                      <span :class="{'bg-danger p-1 rounded-2 text-white': amount_to_pay(order) > 0}">
                        {{ money(amount_to_pay(order)) }}
                      </span>
                    </td>
                    <td>{{ money(order?.price) }}</td>
                    <td>{{ money(order.client_debt) }}</td>
                    <td>
                      <select class="form-select form-select-xsm" :data-order-id="order.id"
                              @click.stop="selectPaymentType" @change.stop="selectPaymentType">
                        <option value="" selected disabled>Выберите способ</option>
                        <option value="cash">Наличные</option>
                        <option value="cashless">Безналичные</option>
                      </select>
                    </td>
                    <td>
                      <button class="btn btn-sm btn-outline-success size08" :data-order-id="order.id"
                              @click.stop="issueOrder">
                        Выдать
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="9" class="fw-bold">Итого</td>
                    <td>
                      <span :class="{'bg-danger p-1 rounded-2 text-white': total_amount_to_pay > 0}">
                        {{ money(total_amount_to_pay) }}
                      </span>
                    </td>
                  </tr>
                </q-table-extended>
              </div>

              <div class="row g-3" v-else-if="view_type === 'card'">
                <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2" v-for="order in orders" :key="order.id">
                  <order-preview-card :order="order"/>
                </div>
              </div>
            </q-loading>

            <div class="row">
              <pagination :pagination="pagination"/>
            </div>
          </div>
        </div>
      </div>


    </div>
  </obertka-adminki>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import {servicSdelok} from "../../api/sdelki/SdelkiServis.js";
import OptsiiSdelok from "../../api/sdelki/OptsiiSdelok.js";
import {getStatusById, OrderStatus, orderStatusTranslate} from "../../utils/zakazi/statusi_zakaza.js";
import OrdersTable from "./komponenti/TablitsaZakazov.vue";
import OrdersFilter from "./komponenti/FiltrZakazov.vue";
import {mapGetters, mapMutations} from "vuex";
import OrdersSearcher from "./komponenti/PoiskovikZakazaN.vue";
import QTableExtended from "../../komponenti/QTableExtended.vue";
import Pagination from "../../komponenti/Pagination.vue";
import {defaultType} from "../../utils/reviewTypes.js";
import QLoading from "../../komponenti/QLoading.vue";
import ViewTypeSwitcher from "./komponenti/PerekluchatelVida.vue";
import OrderPreviewCard from "./komponenti/PreviewKartochkiZakaza.vue";
import shrink_fullname from "../../utils/shrink_fullname.js";
import tipPoiskaSdelki from "./obschie/TipPoiskaSdelki.js";
import {eto_chislo} from "../../utils/numeric.js";

export default {
  name: "OrdersWaitingForCustomerPage",
  components: {
    OrderPreviewCard,
    ViewTypeSwitcher,
    QLoading,
    Pagination,
    QTableExtended,
    OrdersSearcher, OrdersFilter, OrdersTable, ObertkaAdminki, ZagolovokStranitc
  },

  data: () => ({
    page_id: 'waiting_for_customers',

    cols: {
      order_no: 'Номер',
      //order_status: 'Статус',
      warehouse_cells: 'Ячейка',
      customer: 'Клиент',
      shipment_from: 'Откуда',
      // shipment_to: 'Куда',
      // courier: 'Курьер',
      // customer_city: 'Город',
      amount_to_pay: 'К оплате',
      price: 'Цена',
      debt: 'Долг',
      payment_type: 'Способ оплаты',
      actions: 'Действие',
      // issued_at: 'Выдан',
    },

    orders: [],
    pagination: {},

    is_loading: false,
    search_error: '',

    order_payment_type: {}
  }),

  async mounted() {
    await this.loadOrders();

    document.addEventListener('keydown', this.focusOnElement);
  },

  beforeUnmount() {
    document.removeEventListener('keydown', this.focusOnElement);
  },

  computed: {
    OrderStatus() {
      return OrderStatus
    },
    ...mapGetters('orderStore', ['filterEmpty', 'sort_option']),
    ...mapGetters('pageSettingsStore', ['getPageSettings']),
    ...mapGetters('orderSearcher', ['getSearchParams']),

    order_status: () => (id) => {
      return getStatusById(id) || defaultType;
    },

    page_settings() {
      return this.getPageSettings(this.page_id);
    },

    total_amount_to_pay() {
      return this.orders.reduce((total, order) => {
        return total + this.amount_to_pay(order);
      }, 0);
    },

    view_type: {
      get() {
        return this.page_settings.view_type;
      },
      set(value) {
        this.setSettings({view_type: value});
      }
    },

    search_type_id() {
      return this.is_admin_or_manager ? tipPoiskaSdelki.ClientName : tipPoiskaSdelki.OrderNumber;
    },

    _search_params() {
      return this.getSearchParams(this.page_id);
    },

    search_term() {
      return this._search_params?.term?.trim();
    },

    search_type() {
      return this._search_params?.type || this.search_type_id;
    },

  },

  methods: {
    shrink_fullname,
    orderStatusTranslate,

    ...mapMutations('pageSettingsStore', ['SET_SETTINGS_FOR_PAGE']),

    status_color(order) {
      return 'bg-' + this.order_status(order?.order_status).clazz + '-subtle';
    },

    issueOrder(e) {
      const {orderId} = e.target.dataset;

      const payment_type = this.order_payment_type[orderId];

      if (!payment_type) {
        alert('Выберите способ оплаты');
        return;
      }

      servicSdelok()
          .vidat(+orderId, payment_type)
          .then(res => {
            if (res.data.result) {
              this.orders = this.orders.filter(o => o.id !== +orderId);
            }
          });
    },

    selectPaymentType(e) {
      const {orderId} = e.target.dataset;
      this.order_payment_type[orderId] = e.target.value; // order_id - payment type
    },

    focusOnElement(e) {
      const isAlphanumericOrCyrillic = /^[a-zA-Z0-9\u0400-\u04FF]$/;
      if (isAlphanumericOrCyrillic.test(e.key)) {
        const inputElement = document.getElementById('searchTerm');
        inputElement?.focus();
      }
    },

    amount_to_pay(order) {
      return Number(order?.price) + Number(order?.client_debt) + Number(order?.additional_payment);
    },

    onRowClick(e) {
      const {itemId} = e.target.closest('tr').dataset;
      if (!itemId) return;
      this.$router.push({name: 'dostavka.view', params: {id: itemId}});
    },

    setSettings(settings) {
      this.SET_SETTINGS_FOR_PAGE({page: this.page_id, settings});
    },

    createFilterOptions() {
      const opts = new OptsiiSdelok();
      opts.query = this.$route.query;
      opts.filters.addresses_from = [this.delivery_point_id];

      return opts;
    },

    async loadOrders() {
      const opts = this.createFilterOptions();

      if(this.search_term) {
        if(this.search_type === tipPoiskaSdelki.OrderNumber) {
          if(eto_chislo(this.search_term)) {
            opts.filters.order_id = this.search_term;
          }
        }

        if(this.search_type === tipPoiskaSdelki.ClientName) {
          opts.filters.customer_name = this.search_term;
        }
      }

      const {data} = await servicSdelok().ojidaiutKlientov(opts);
      this.orders = data.data;
      this.pagination = data;
    },

    onSearchLoading(is_loading) {
      this.is_loading = is_loading;
    },

    onSearchError(e) {
      if (404 === e?.response?.status) {
        this.search_error = 'Не удается найти заказ по введенным данным';
      }
    },

    onSearchCompleted(data) {
      if (data?.data) this.search_error = '';
      this.orders = data && data?.data || [];
      this.pagination = data || {};
    },

    async onSearchClear() {
      this.search_error = '';

      await this.loadOrders();
    },

    async onFilter() {
      this.$router.push({path: this.$route.path, query: {}}).catch(err => {
        if (err.name !== 'NavigationDuplicated') {
          throw err;
        }
      });

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