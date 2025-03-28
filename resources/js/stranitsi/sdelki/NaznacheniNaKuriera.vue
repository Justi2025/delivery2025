/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>

    <zagolovok-stranitc :caption="caption()">
      <router-link :to="url('dostavka.assigned_to_courier')" class="btn btn-sm btn-outline-warning"
                   v-if="delivery_point_available">
        Вернуться
      </router-link>
    </zagolovok-stranitc>

    <div class="mx-1" v-if="delivery_point_available">

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

      <template v-if="orders.length > 0">
        <div class="row g-3" v-if="view_type === 'list'">
          <q-table-extended :cols="role_cols" @click="openOrderCard">
            <tr v-for="(order, counter) in orders" :data-item-id="order.id" class="cursor-pointer"
                :class="{'table-primary': order?.destination_type === 3}"
                :key="order.id">
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
                <!-- <td>{{ order.customer_city || '-' }}</td>-->
                <!--<td>{{ order.amount_to_pay || '-' }}</td>
                <td>{{ order.price || '0' }}</td>
                <td>{{ order.client_debt || '-' }}</td>-->
                <td>{{ dt_format2(order.created_at) }}</td>
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
        Нет заказов в выбранной папке
      </h4>

    </div>

    <div class="row" v-else-if="delivery_points?.length > 0">
      <div class="col-sm-6 col-md-6 col-lg-3 mb-3" v-for="dp in delivery_points">
        <delivery-point-card-clickable
            :dp="dp"
            :to="url('dostavka.assigned_to_courier.dp', { dp_id: dp.shipping_from_id })"
            :key="dp.shipping_from_id"/>
      </div>
    </div>

    <h4 class="h4" v-else>
      Нет заказов для выполнения
    </h4>

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
import Pagination from "../../komponenti/Pagination.vue";
import ViewTypeSwitcher from "./komponenti/PerekluchatelVida.vue";
import OrderPreviewCard from "./komponenti/PreviewKartochkiZakaza.vue";
import OrdersFilter from "./komponenti/FiltrZakazov.vue";
import {mapGetters, mapMutations} from "vuex";
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
    OrdersFilter,
    OrderPreviewCard,
    ViewTypeSwitcher,
    Pagination,
    OrdersTable, DeliveryPointCard, ObertkaAdminki, ZagolovokStranitc
  },

  data: () => ({
    page_id: 'assigned_to_courier',
    delivery_points: [],

    cols: {
      order_no: 'Номер',
      order_status: 'Статус',
      customer: 'Клиент',
      shipment_from: 'Откуда',
      shipment_to: 'Куда',
      courier: 'Курьер',
      created_at: 'Создан',
    },

    orders: [],
    pagination: {}
  }),

  async mounted() {
    if (this.delivery_point_available) {
      await this.loadOrders();
    } else {
      await this.loadGroupedByDeliveryPoints();
    }

  },

  computed: {

    ...mapGetters('ordersFilterStore', ['getFiltersAndSortOptions', 'isFilterEmpty']),
    ...mapGetters('pageSettingsStore', ['getPageSettings']),
    ...mapGetters('authStore', ['user']),

    role_cols() {
      if(this.is_courier) {
        return {
          order_no: this.cols.order_no,
          customer: this.cols.customer,
          shipment_from: this.cols.shipment_from,
        }
      }

      return this.cols;
    },

    delivery_point_available() {
      return !!this.$route.params?.dp_id;
    },

    delivery_point_id() {
      return +this.$route.params?.dp_id;
    },

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

  },

  methods: {
    shrink_fullname,
    getStatusById,
    orderStatusTranslate,

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

    createFilterOptions() {
      const opts = new OptsiiSdelok();
      opts.query = this.$route.query;
      opts.filters.order_statuses = [OrderStatus.AssignedToCourier];
      opts.filters.addresses_from = [this.delivery_point_id];

      return opts;
    },

    async loadGroupedByDeliveryPoints() {

      try {
        const opts = this.createFilterOptions();
        const {data} = await servicSdelok().getGroupedByDeliveryPoints(opts.query, opts.filters, opts.sort);

        // Todo: this is grouped orders not dps
        this.delivery_points = data.data;
        this.pagination = data;

      } catch (e) {
        const status = e?.response?.status || 0;

        if (404 === status) {
          this.delivery_points = [];
          this.pagination = {};
        } else {
          this.error_message = e?.response?.data?.message;
        }
      }
    },

    /**
     *
     * @return {Promise<void>}
     */
    async loadOrders() {

      try {
        const {data} = await servicSdelok().zakaziNaKuriera(this.filter_opts, this.delivery_point_id);
        this.orders = data.data;
        this.pagination = data;
      } catch (e) {
        const status = e?.response?.status || 0;

        if (404 === status) {
          this.orders = [];
          this.pagination = {};
          this.redirect_to('dostavka.assigned_to_courier');
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