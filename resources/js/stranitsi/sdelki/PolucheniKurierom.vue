/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>

    <zagolovok-stranitc :caption="caption()">
      <router-link :to="url('dostavka.received_by_courier')" class="btn btn-sm btn-outline-warning"
                   v-if="delivery_point_available">
        Вернуться
      </router-link>
    </zagolovok-stranitc>

    <div class="mx-1" v-if="delivery_point_available || this.is_storekeeper">

      <orders-filter :filter-id="page_id" @on-filter="onFilter" @on-filter-clear="onFilterClear"
                     @on-orders-sort="onOrdersSort"/>

      <div class="row g-2 align-items-center mb-2">
        <div class="col">
          <input type="button" value="Фильтры" class="btn btn-sm mb-3"
                 :class="{'btn-outline-warning': filter_empty, 'btn-warning': !filter_empty}"
                 data-bs-target="#orderFiltersModal" data-bs-toggle="modal"
          />
        </div>
        <div class="col mt-0 d-flex justify-content-end visually-hidden">
          <view-type-switcher v-model="view_type"/>
        </div>
      </div>

      <div class="row gy-3">
        <div class="col-md-12">
          <orders-searcher
              :order-statuses="[OrderStatus.ReceivedByCourier]"
              @on-search-completed="onSearchCompleted" @on-error="onSearchError"
              @on-search-clear="onSearchClear" @on-loading="onSearchLoading"
          />
          <div class="text-danger">
            {{ search_error }}
          </div>
        </div>
      </div>

      <template v-if="orders.length > 0">
        <div class="row g-3" v-if="view_type === 'list'">
          <q-table-extended :cols="role_cols">
            <tr v-for="(order, counter) in orders" :data-item-id="order.id" class="cursor-pointer"
                :class="{'table-primary': order?.destination_type === 3}"
                :key="order.id"
                @click="openOrderCard"
            >
              <th scope="row">{{ ++counter }}</th>
              <template v-if="is_courier">
                <td>
                  <router-link :to="route_url('dostavka.view', { id: order.id }).fullPath">
                    #{{ format_order_no(order.id) }}
                  </router-link>
                </td>
                <td>{{ order.customer }}</td>
                <td>
                <span :style="{backgroundColor: order?.shipment_from_color, color: order?.shipment_from_label_color}"
                      style="padding: 3px; font-size: .85rem" class="rounded-2">
                  {{ order.shipment_from }}
                </span>
                </td>
                <td>{{ money(order.client_debt) }}</td>
              </template>
              <template v-else>
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
                <td>{{ order.customer }}</td>
                <td>
                <span :style="{backgroundColor: order?.shipment_from_color, color: order?.shipment_from_label_color}"
                      style="padding: 3px; font-size: .85rem" class="rounded-2">
                  {{ order.shipment_from }}
                </span>
                </td>
                <td>{{ order.shipment_to }}</td>
                <td>{{ order.courier }}</td>
                <td>{{ money(order.client_debt) }}</td>
                <td>{{ dt_format2(order.created_at) }}</td>
                <td>{{ dt_format2(order.received_at) }}</td>
              </template>
            </tr>
          </q-table-extended>
        </div>

        <div class="row g-3" v-else-if="view_type === 'card'">
          <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2" v-for="order in orders" :key="order.id">
            <order-preview-card :order="order"/>
          </div>
        </div>

        <div class="row mt-2">
          <pagination :pagination="pagination"/>
        </div>

      </template>
      <h4 class="h4" v-else>
        Нет данных
      </h4>

    </div>

    <div class="row" v-else>
      <div class="col-sm-6 col-md-6 col-lg-3 mb-3" v-for="dp in delivery_points" v-if="delivery_points.length > 0">
        <delivery-point-card-clickable :dp="dp"
                                       :to="url('dostavka.received_by_courier.dp', { dp_id: dp.shipping_from_id })"
                                       :key="dp.shipping_from_id"/>
      </div>
      <div class="col-md-12" v-else>
        <div class="mx-1">Нет заказов в выбранном разделе</div>
      </div>
    </div>

  </obertka-adminki>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import {servicSdelok} from "../../api/sdelki/SdelkiServis.js";
import DeliveryPointCard from "./komponenti/KartochkaPvz.vue";
import OrdersTable from "./komponenti/TablitsaZakazov.vue";
import OptsiiSdelok from "../../api/sdelki/OptsiiSdelok.js";
import {getStatusById, OrderStatus, orderStatusTranslate} from "../../utils/zakazi/statusi_zakaza.js";
import OrdersFilter from "./komponenti/FiltrZakazov.vue";
import {mapGetters, mapMutations} from "vuex";
import ViewTypeSwitcher from "./komponenti/PerekluchatelVida.vue";
import OrderPreviewCard from "./komponenti/PreviewKartochkiZakaza.vue";
import Pagination from "../../komponenti/Pagination.vue";
import QTableExtended from "../../komponenti/QTableExtended.vue";
import shrink_fullname from "../../utils/shrink_fullname.js";
import DeliveryPointCardClickable from "./komponenti/KartochkaPvzKlikabelnaia.vue";
import OrdersSearcher from "./komponenti/PoiskovikZakazaN.vue";

export default {
  name: "AcceptedOrdersPage",
  components: {
    OrdersSearcher,
    DeliveryPointCardClickable,
    QTableExtended,
    Pagination,
    OrderPreviewCard,
    ViewTypeSwitcher, OrdersFilter, OrdersTable, DeliveryPointCard, ObertkaAdminki, ZagolovokStranitc
  },

  data: () => ({
    page_id: 'received_by_courier',
    delivery_points: [],

    /*cols: {
      order_no: 'Номер',
      order_status: 'Статус',
      shipment_from: 'Откуда',
      shipment_to: 'Куда',
      courier: 'Курьер',
      customer: 'Клиент',
      customer_city: 'Город',
      amount_to_pay: 'К оплате',
      price: 'Цена',
      debt: 'Долг',
      created_at: 'Создан',
      // received_at: 'Получен',
      // issued_at: 'Выдан',
    },*/

    cols: {
      order_no: 'Номер',
      order_status: 'Статус',
      customer: 'Клиент',
      shipment_from: 'Откуда',
      shipment_to: 'Куда',
      courier: 'Курьер',
      //customer_city: 'Город',
      //amount_to_pay: 'К оплате',
      //price: 'Цена',
      debt: 'Долг',
      created_at: 'Создан',
      received_at: 'Получен',
      // issued_at: 'Выдан',
    },

    orders: [],
    pagination: {},
    is_loading: false,
    search_error: ''
  }),

  async mounted() {
    if (this.delivery_point_available || this.is_storekeeper) {
      await this.loadOrders();
    } else {
      await this.loadGroupedByDeliveryPoints();
    }
  },

  computed: {
    OrderStatus() {
      return OrderStatus
    },

    ...mapGetters('ordersFilterStore', ['getFiltersAndSortOptions', 'isFilterEmpty']),
    ...mapGetters('pageSettingsStore', ['getPageSettings']),

    filter_opts() {
      return this.getFiltersAndSortOptions(this.page_id);
    },

    filter_empty() {
      return this.isFilterEmpty(this.page_id);
    },

    delivery_point_available() {
      return !!this.$route.params?.dp_id;
    },

    delivery_point_id() {
      return +this.$route.params?.dp_id || 0;
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

    role_cols() {
      if (this.is_courier) {
        return {
          order_no: this.cols.order_no,
          customer: this.cols.customer,
          shipment_from: this.cols.shipment_from,
          debt: this.cols.debt,
        }
      }

      return this.cols;
    },
  },

  methods: {
    shrink_fullname,
    orderStatusTranslate,
    getStatusById,

    ...mapMutations('pageSettingsStore', ['SET_SETTINGS_FOR_PAGE']),


    setSettings(settings) {
      this.SET_SETTINGS_FOR_PAGE({page: this.page_id, settings});
    },

    caption() {
      if (this.delivery_point_available) {
        return 'ПВЗ ' + this.orders[0]?.shipment_from;
      }

      return this.$route.meta.caption;
    },

    onSearchLoading(is_loading) {
      // console.log('is_loading: ', is_loading)
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

    createFilterOptions() {
      const opts = new OptsiiSdelok();
      opts.query = this.$route.query;
      opts.filters.order_statuses = [OrderStatus.ReceivedByCourier];
      opts.filters.addresses_from = [this.delivery_point_id];

      return opts;
    },

    async loadGroupedByDeliveryPoints() {
      const opts = this.createFilterOptions();
      const {data} = await servicSdelok().getGroupedByDeliveryPoints(opts.query, opts.filters, opts.sort);

      this.delivery_points = data.data;
      this.pagination = data;
    },

    /**
     *
     * @return {Promise<void>}
     */
    async loadOrders() {

      try {
        const {data} = await servicSdelok().poluchitPoluchennieKurieromZakazi(this.filter_opts, this.delivery_point_id);
        this.orders = data.data;
        this.pagination = data;
      } catch (e) {
        const status = e?.response?.status || 0;

        if (404 === status) {
          this.redirect_to('dostavka.received_by_courier');
        } else {
          this.error_message = e?.response?.data?.message;
        }
      }
    },


    async onFilter() {
      // Go to first page if results less then paginator per_page
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