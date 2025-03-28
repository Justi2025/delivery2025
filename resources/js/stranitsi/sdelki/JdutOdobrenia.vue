
<template>
  <obertka-adminki>
    <zagolovok-stranitc :caption="$route.meta.caption"/>

    <image-modal id="orderBarcodeModal" :image="order_photo"/>
    <image-slider-modal id="orderBarcodesModal" :images="barcodes" />

    <div class="mx-1">

      <orders-filter-x id="pending" @on-filter="onFilter" @on-filter-clear="onFilterClear"
                       @on-orders-sort="onOrdersSort"/>

      <data-or-fallback :data="orders">

        <div class="row g-2 align-items-center mb-2">
          <div class="col">
            <input type="button" value="Фильтры" class="btn btn-sm mb-3"
                   :class="{'btn-outline-warning': filterEmpty, 'btn-warning': !filterEmpty}"
                   data-bs-target="#orderFiltersModal" data-bs-toggle="modal"
            />
          </div>
          <div class="col mt-0 d-flex justify-content-end visually-hidden">
            <view-type-switcher v-model="view_type"/>
          </div>
        </div>

        <div class="row g-3" v-if="view_type === 'list'">
          <q-table-extended :cols="cols" @click="openOrderCard">
            <tr v-for="(order, counter) in orders" :data-item-id="order.id" class="cursor-pointer" :key="order.id">
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
                <barcode-image :name="order.barcode_image" target="#orderBarcodeModal" @on-click="onBarcodeImageClick" />
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
              <td class="shortened-text-parent">
                <div :class="{'shortened-text': order.customer_comment}">
                  {{ order?.customer_comment }}
                </div>
              </td>
              <td>
                <button class="btn btn-sm btn-outline-success size08" :data-order-id="order.id" @click="onOrderAccept">
                  Принять
                </button>
              </td>
            </tr>
          </q-table-extended>
        </div>

        <div class="row g-3" v-else-if="view_type === 'card'">
          <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2" v-for="order in orders" :key="order.id">
            <order-preview-card :order="order" @on-order-accept="onOrderAccept" has_accept_button />
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
  </obertka-adminki>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import {servicSdelok} from "../../api/sdelki/SdelkiServis.js";
import OrdersTable from "./komponenti/TablitsaZakazov.vue";
import OrdersFilter from "./komponenti/FiltrZakazov.vue";
import {mapGetters, mapMutations} from "vuex";
import OrdersTableX from "./komponenti/TablitsaZakazovX.vue";
import DataOrFallback from "../../komponenti/DataOrFallback.vue";
import Pagination from "../../komponenti/Pagination.vue";
import QTableExtended from "../../komponenti/QTableExtended.vue";
import {getStatusById, OrderStatus, orderStatusTranslate} from "../../utils/zakazi/statusi_zakaza.js";
import shrink_fullname from "../../utils/shrink_fullname.js";
import BarcodeImage from "./komponenti/ShtrikhKodIzobrajenie.vue";
import ImageModal from "./komponenti/ModalIzobrajenii.vue";
import OrdersFilterX from "./komponenti/FiltrZakazovX.vue";
import ViewTypeSwitcher from "./komponenti/PerekluchatelVida.vue";
import OrderPreviewCard from "./komponenti/PreviewKartochkiZakaza.vue";
import ImageSliderModal from "./komponenti/SlaiderIzobrajeniiVModale.vue";
import BarcodesButton from "./komponenti/KnopkaPokazShtrikhov.vue";

export default {
  name: "PendingOrdersPage",
  components: {
    BarcodesButton,
    ImageSliderModal,
    OrderPreviewCard,
    ViewTypeSwitcher,
    OrdersFilterX,
    ImageModal,
    BarcodeImage,
    QTableExtended,
    Pagination,
    DataOrFallback, OrdersTableX, OrdersFilter, OrdersTable, ObertkaAdminki, ZagolovokStranitc
  },

  data: () => ({
    page_id: 'pending',

    cols: {
      id: 'Номер',
      order_status: 'Статус',
      customer: 'Клиент',
      shipment_from: 'Откуда',
      shipment_to: 'Куда',
      barcode_image: 'Штрих-код',
      barcode_images: 'Штрихи',
      created_at: 'Создан',
      customer_comment: 'Комментарий',
      actions: 'Действия',
    },

    orders: [],
    pagination: {},

    order_photo: '',
    barcodes: []
  }),

  async mounted() {
    await this.loadOrders();
  },

  computed: {
    ...mapGetters('ordersFilterStore', ['getFiltersForPage', 'isFilterEmpty', 'getFiltersAndSortOptions']),
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

    filterEmpty() {
      return this.isFilterEmpty('pending');
    }
  },

  methods: {
    getStatusById,
    shrink_fullname,
    orderStatusTranslate,

    ...mapMutations('pageSettingsStore', ['SET_SETTINGS_FOR_PAGE']),


    setSettings(settings) {
      this.SET_SETTINGS_FOR_PAGE({page: this.page_id, settings});
    },

    async onOrderAccept(e) {
      const orderId = +e.target.dataset.orderId;

      const order_data = {
        order_id: orderId,
        status_code: OrderStatus.Accepted,
      };

      const res = await servicSdelok().izmenitStatusSdelki(order_data);

      if (res.data) {
        this.orders = this.orders.filter(o => o.id !== orderId);
        alert("Статус заказа изменен");
      } else
        alert("Ошибка при изменении статуса заказа");
    },

    onBarcodeImageClick(image) {
      this.order_photo = image;
    },

    onBarcodesShow(barcodes) {
      this.barcodes = barcodes;
    },

    transformResponseData(order) {
      const newObj = {};
      for (const col in this.cols) {
        newObj[col] = order[col];
      }
      return newObj;
    },


    async loadOrders() {
      const opts = this.getFiltersAndSortOptions('pending');
      // console.log(opts);

      try {

        const {data} = await servicSdelok().getPendingOrders(opts);
        this.orders = data.data;
        /*this.orders = data.data.map(order => {
          return this.transformResponseData(order);
        });*/
        this.pagination = data;
      } catch (e) {
        this.orders = [];
        this.pagination = {};
      }
    },

    async onFilter() {
      // Todo: Prevent page reload
      // Changing of query needed as when query results less than 15 (default for paginator) and when on ?page=2
      // no results returned, so we should return to the first page
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

.shortened-text {
  font-size: .85rem !important;
  max-width: 64px;
  height: 52px;
  text-overflow: ellipsis;
  transition: height 0.5s ease;
  overflow: hidden;
}

.shortened-text-parent:hover .shortened-text {
  height: 300px;
}
</style>