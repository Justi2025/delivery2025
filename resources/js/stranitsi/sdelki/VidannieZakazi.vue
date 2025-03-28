
<template>
  <obertka-adminki>
    <zagolovok-stranitc :caption="$route.meta.caption"/>

    <div class="mx-1">

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
          <q-table-extended :cols="cols">
            <tr v-for="(order, counter) in orders" :data-item-id="order.id" class="cursor-pointer" :key="order.id"
                @click="openOrderCard">
              <th scope="row">
                {{ row_counter(counter, pagination.current_page, pagination.per_page) }}
              </th>
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
              <template v-if="true">
                <!-- <td>{{ shrink_fullname(order.courier) || '-' }}</td> -->
                <td>{{ order.customer }}</td>
                <!-- <td>{{ order.customer_city || '-' }}</td> -->
              </template>
              <td>
            <span :style="{backgroundColor: order?.shipment_from_color, color: order?.shipment_from_label_color}"
                style="padding: 3px; font-size: .85rem" class="rounded-2">
            {{ order.shipment_from }}
            </span>
              </td>
              <td>{{ order.shipment_to }}</td>
              <td>{{ money(order.amount_to_pay + order.additional_payment) }}</td>
              <td>{{ money(order.price) }}</td>
              <!-- <td>{{ money(order.client_debt) }}</td> -->
              <td>{{ dt_format2(order.issued_at) }}</td>
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

  </obertka-adminki>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import OrdersTable from "./komponenti/TablitsaZakazov.vue";
import {servicSdelok} from "../../api/sdelki/SdelkiServis.js";
import OrdersFilterX from "./komponenti/FiltrZakazovX.vue";
import {mapGetters, mapMutations} from "vuex";
import OrdersFilter from "./komponenti/FiltrZakazov.vue";
import OrderPreviewCard from "./komponenti/PreviewKartochkiZakaza.vue";
import Pagination from "../../komponenti/Pagination.vue";
import ViewTypeSwitcher from "./komponenti/PerekluchatelVida.vue";
import QTableExtended from "../../komponenti/QTableExtended.vue";
import {getStatusById, orderStatusTranslate} from "../../utils/zakazi/statusi_zakaza.js";
import {defaultType} from "../../utils/reviewTypes.js";

export default {
  name: "IssuedOrdersPage",
  components: {
    QTableExtended,
    ViewTypeSwitcher,
    Pagination,
    OrderPreviewCard,
    OrdersFilter, OrdersFilterX, OrdersTable, ObertkaAdminki, ZagolovokStranitc
  },

  data: () => ({
    page_id: 'issued',

    cols: {
      order_no: 'Номер',
      order_status: 'Статус',
      customer: 'Клиент',
      shipment_from: 'Откуда',
      shipment_to: 'Куда',
      amount_to_pay: 'К оплате',
      price: 'Цена',
      issued_at: 'Выдан',
    },

    orders: [],
    pagination: {}
  }),

  async mounted() {
    await this.loadOrders();
  },

  computed: {
    ...mapGetters('ordersFilterStore', ['isFilterEmpty', 'getFiltersAndSortOptions']),
    ...mapGetters('pageSettingsStore', ['getPageSettings']),

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
  },

  methods: {
    orderStatusTranslate,

    ...mapMutations('pageSettingsStore', ['SET_SETTINGS_FOR_PAGE']),


    setSettings(settings) {
      this.SET_SETTINGS_FOR_PAGE({page: this.page_id, settings});
    },

    async loadOrders() {
      const opts = this.getFiltersAndSortOptions(this.page_id);
      try {

        const {data} = await servicSdelok().vidaniKlientam(opts);
        this.orders = data.data;
        this.pagination = data;
      } catch (e) {
        this.orders = [];
        this.pagination = {};
      }
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