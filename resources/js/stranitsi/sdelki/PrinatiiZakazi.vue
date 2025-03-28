/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>

    <zagolovok-stranitc :caption="$route.meta.caption" class="d-flex align-items-center">
      <router-link :to="url('dostavka.accepted')" class="btn btn-sm btn-outline-warning" v-if="from_id_available">
        Вернуться
      </router-link>
    </zagolovok-stranitc>

    <image-modal id="orderBarcodeModal" :image="order_photo"/>
    <image-slider-modal id="orderBarcodesModal" :images="barcodes" />

    <div class="mx-1">
      <div class="row mb-3 actions d-flex align-items-center"
           :class="{'actions--visible': dp_count > 0 || selected_orders_count > 0}">
        <div class="col-auto">Выбрано: {{ dp_count || selected_orders_count }}</div>
        <div class="col-auto">
          <select class="form-select form-select-sm" @change="onCourierSelected">
            <option selected disabled value="">Выберите курьера</option>
            <option :value="courier.id" v-for="courier in couriers" :key="courier.id">{{ courier.value }}</option>
          </select>
        </div>
      </div>
    </div>

    <div class="mx-1" v-if="from_id_available">

      <orders-filter :filter-id="page_id" @on-filter="onFilter" @on-filter-clear="onFilterClear"
                     @on-orders-sort="onOrdersSort"/>

      <data-or-fallback :data="orders">

        <div class="row g-2 actions align-items-center mb-2" :class="{'actions--visible': !selected_orders_count }">
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

        <div class="row g-3" v-if="view_type === 'list'">
          <q-table-extended :cols="cols" @click="onRowClick">
            <tr v-for="(order, counter) in orders" :data-item-id="order.id" class="cursor-pointer"
                :class="{'table-primary': is_order_selected(order.id)}" :key="order.id"
            >
              <th scope="row">{{ ++counter }}</th>
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
              <td>
                <barcode-image :name="order.barcode_image" target="#orderBarcodeModal" @on-click="onBarcodeImageClick"/>
              </td>
              <td>
                <barcodes-button
                    target="#orderBarcodesModal"
                    :barcodes="order.barcodes"
                    @on-click="onBarcodesShow"
                    v-if="order?.barcodes?.length > 0"
                />
                <span v-else>-</span>
              </td>
              <td>{{ dt_format2(order.created_at) }}</td>
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

        <template #fallback>
          <h4 class="h4">
            Нет данных
          </h4>
        </template>
      </data-or-fallback>
    </div>

    <div class="mx-1" v-else>
      <div class="row">
        <div class="col-sm-6 col-md-6 col-lg-3 mb-3" v-for="dp in delivery_points">
          <delivery-point-card :dp="dp"
                               :to="url('accepted.from_id', { from_id: dp.shipping_from_id })"
                               :selected="is_dp_selected(dp.shipping_from_id)"
                               @on-click="onCardClick"
          />
        </div>
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
import {mapGetters, mapMutations, mapState} from "vuex";
import BarcodeImage from "./komponenti/ShtrikhKodIzobrajenie.vue";
import Pagination from "../../komponenti/Pagination.vue";
import QTableExtended from "../../komponenti/QTableExtended.vue";
import DataOrFallback from "../../komponenti/DataOrFallback.vue";
import shrink_fullname from "../../utils/shrink_fullname.js";
import OrdersFilter from "./komponenti/FiltrZakazov.vue";
import OrdersFilterX from "./komponenti/FiltrZakazovX.vue";
import {toRaw} from "vue";
import ViewTypeSwitcher from "./komponenti/PerekluchatelVida.vue";
import OrderPreviewCard from "./komponenti/PreviewKartochkiZakaza.vue";
import ImageModal from "./komponenti/ModalIzobrajenii.vue";
import ImageSliderModal from "./komponenti/SlaiderIzobrajeniiVModale.vue";
import BarcodesButton from "./komponenti/KnopkaPokazShtrikhov.vue";

export default {
  name: "AcceptedOrdersPage",
  components: {
    BarcodesButton,
    ImageSliderModal,
    ImageModal,
    OrderPreviewCard,
    ViewTypeSwitcher,
    OrdersFilterX,
    OrdersFilter,
    DataOrFallback,
    QTableExtended,
    Pagination,
    BarcodeImage,
    OrdersTable, DeliveryPointCard, ObertkaAdminki, ZagolovokStranitc
  },

  data: () => ({
    page_id: 'accepted',
    delivery_points: [],

    cols: {
      order_no: 'Номер',
      order_status: 'Статус',
      customer: 'Клиент',
      shipment_from: 'Откуда',
      shipment_to: 'Куда',
      courier: 'Штрих-код',
      barcodes: 'Штрихи',
      created_at: 'Создан',
    },

    orders: [],
    pagination: {},

    selected_delivery_points: [],
    selected_orders: [],
    order_photo: '',
    barcodes: [],
  }),

  async mounted() {
    if (this.from_id_available) {
      await this.loadOrders();
    } else {
      await this.loadGroupedByCards();
    }
  },

  computed: {

    ...mapState(['couriers']),
    ...mapGetters('ordersFilterStore', ['getFiltersAndSortOptions', 'isFilterEmpty']),
    ...mapGetters('pageSettingsStore', ['getPageSettings']),

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


    from_id_available() {
      return !!this.$route.params?.from_id;
    },

    from_id() {
      return +this.$route.params?.from_id;
    },

    dp_count() {
      return this.selected_delivery_points.length;
    },

    filter_opts() {
      return this.getFiltersAndSortOptions(this.page_id);
    },

    filter_empty() {
      return this.isFilterEmpty(this.page_id);
    },

    selected_orders_count() {
      return this.selected_orders.length;
    }
  },

  methods: {
    shrink_fullname,
    getStatusById,
    orderStatusTranslate,

    ...mapMutations('pageSettingsStore', ['SET_SETTINGS_FOR_PAGE']),


    setSettings(settings) {
      this.SET_SETTINGS_FOR_PAGE({page: this.page_id, settings});
    },

    onBarcodesShow(barcodes) {
      this.barcodes = barcodes;
    },


    toggleForm() {
      this.isFormOpen = !this.isFormOpen;
    },

    is_dp_selected(id) {
      return !!this.selected_delivery_points.find(dp => +dp.shipping_from_id === +id);
    },

    is_order_selected(id) {
      return !!this.selected_orders.find(_id => +_id === +id);
    },

    onRowClick(e) {

      if (e.target.classList.contains('barcode-image')) {
        return;
      }

      const tr = e.target.closest('tr');
      const orderId = +tr.dataset.itemId;

      const found_id = this.selected_orders?.find(id => id === +orderId);
      if (found_id) {
        this.selected_orders = this.selected_orders.filter(id => +id !== +found_id);
      } else {
        this.selected_orders = [...this.selected_orders, orderId];
      }
    },

    onBarcodeImageClick(image) {
      this.order_photo = image;
    },

    onCardClick(dp) {
      const found = this.selected_delivery_points.find(sdp => +sdp.shipping_from_id === +dp.shipping_from_id);

      if (found) {
        this.selected_delivery_points = this.selected_delivery_points.filter(sdp => +sdp.shipping_from_id !== +found.shipping_from_id);
      } else {
        this.selected_delivery_points = [...this.selected_delivery_points, dp];
      }
    },

    createFilterOptions() {
      const opts = new OptsiiSdelok();
      opts.query = this.$route.query;
      opts.filters.order_statuses = [OrderStatus.Accepted];
      opts.filters.addresses_from = [this.from_id];

      return opts;
    },

    async loadGroupedByCards() {
      const opts = this.createFilterOptions();
      const {data} = await servicSdelok().getGroupedByDeliveryPoints(opts.query, opts.filters);
      this.delivery_points = data.data.map(c => {
        c['order_ids'] = c['order_ids'].split(',').map(Number);
        return c;
      });
      this.pagination = data;
    },

    async loadOrders() {
      const {data} = await servicSdelok().poluchitPrinatieZakazi(this.filter_opts, this.$route.params.from_id);
      this.orders = data.data;
      this.pagination = data;
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


    getOrderIds() {
      if (this.selected_orders.length > 0) {
        return toRaw(this.selected_orders);
      } else {
        return this.selected_delivery_points.reduce((acc, dp) => [...acc, ...dp.order_ids], []);
      }
    },


    async onCourierSelected(e) {
      const courier_id = +e.target.value;

      const dp_name = (dp) => `${dp.dp_name}, ${dp.dp_street} ${dp.dp_house}`;
      const courier_name = this.couriers.find(c => c.id === courier_id)?.value;

      const dps = this.selected_delivery_points.map(dp_name).join('\n');
      const selected_dp_ids = this.selected_delivery_points.map(dp => dp.shipping_from_id);

      const message = this.selected_orders.length > 0 ? `
        Вы действительно хотите назначить курьера ${courier_name} на выбранные заказы?
      ` : `
        Вы действительно хотите назначить курьера ${courier_name} на все заказы из следующих ПВЗ: \n\n ${dps}?
      `

      if (confirm(message?.trim())) {
        const order_ids = this.getOrderIds();

        servicSdelok()
            .naznachitNaKuriera(courier_id, order_ids)
            .then(() => {

              if (this.dp_count > 0) {
                this.delivery_points = this.delivery_points.filter(dp => !selected_dp_ids.includes(dp.shipping_from_id));
                this.selected_delivery_points = [];
              }

              if (this.selected_orders_count > 0) {
                this.orders = this.orders.filter(o => !this.selected_orders.includes(o.id));
                this.selected_orders = [];
              }
              alert('Курьер успешно назначен');
            })
            .catch(console.error)
      } else {
        alert('Курьер не выбран');
      }

    }
  },
}
</script>

<style scoped>
.actions {
  max-height: 0;
  overflow: hidden;
  transition: all 0.45s ease-in-out;
}

.actions--visible {
  max-height: 64px;
}
</style>