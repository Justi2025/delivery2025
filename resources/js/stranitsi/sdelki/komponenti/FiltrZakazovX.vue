
<script>
import Multiselect from "../../../komponenti/Multiselect.vue";
import ModalDialog from "../../../komponenti/ModalDialog.vue";
import {mapGetters, mapMutations, mapState} from "vuex";
import {defaultType, getStatusById, statusi_zakaza, orderStatusTranslate} from "../../../utils/zakazi/statusi_zakaza.js";
import optsciiSortirovkiSdelok from "../obschie/optsciiSortirovkiSdelok.js";
import OrderSearcherX from "./PoiskovikZakaza.vue";

export default {
  name: "OrdersFilterX",
  components: {OrderSearcherX, ModalDialog, Multiselect},

  emits: ['onOrdersSort', 'onFilterClear', 'onFilter'],

  props: {
    id: {
      type: String,
      required: true
    }
  },

  data: () => ({
    order_statuses: [],
  }),

  async mounted() {
    this.order_statuses = statusi_zakaza().map(s => ({id: s.code, value: s.caption}));
  },

  computed: {

    ...mapState(['couriers', 'addresses', 'cities']),
    ...mapGetters('ordersFilterStore', ['getFiltersForPage', 'isFilterEmpty', 'getSortForPage']),

    order_status: () => (id) => {
      return getStatusById(id) || defaultType;
    },

    orderSortOptions() {
      return optsciiSortirovkiSdelok;
    },

    filter() {
      return this.getFiltersForPage(this.id);
    },

    filter_couriers: {
      get() {
        return this.filter.couriers;
      },
      set(value) {
        this.setFilter({ couriers: value });
      }
    },

    filter_order_statuses: {
      get() {
        return this.filter.order_statuses;
      },
      set(value) {
        this.setFilter({order_statuses: value});
      }
    },

    filter_addresses_from: {
      get() {
        return this.filter.addresses_from;
      },
      set(value) {
        this.setFilter({addresses_from: value});
      }
    },

    filter_addresses_to: {
      get() {
        return this.filter.addresses_to;
      },
      set(value) {
        this.setFilter({addresses_to: value});
      }
    },

    filter_start_datetime: {
      get() {
        return this.filter.start_datetime;
      },
      set(value) {
        this.setFilter({start_datetime: value});
      }
    },

    filter_end_datetime: {
      get() {
        return this.filter.end_datetime;
      },
      set(value) {
        this.setFilter({end_datetime: value});
      }
    },

    filter_customer_cities: {
      get() {
        return this.filter.customer_cities;
      },
      set(value) {
        this.setFilter({customer_cities: value});
      }
    },

    filter_customer_id: {
      get() {
        return this.filter.customer_id;
      },
      set(value) {
        this.setFilter({customer_id: value});
      }
    },

    selected_sort_option: {
      get() {
        return this.getSortForPage(this.id);
      },
      set(value) {
        this.SET_SORT_FOR_PAGE({page: this.id, sort: value});
      }
    },

  },

  methods: {
    orderStatusTranslate,

    ...mapMutations('ordersFilterStore', ['SET_FILTERS_FOR_PAGE', 'SET_SORT_FOR_PAGE', 'CLEAR_FILTERS_FOR_PAGE']),

    setFilter(filter) {
      this.SET_FILTERS_FOR_PAGE({page: this.id, filter});
    },

    onCustomerSelect(customer_id) {
      this.filter_customer_id = customer_id;
    },

    async onOrdersSort() {
      this.$emit('onOrdersSort');
    },

    async onFilter() {
      this.$emit('onFilter');
    },

    async onFilterClear() {
      this.CLEAR_FILTERS_FOR_PAGE(this.id);

      this.$emit('onFilterClear');
    },
  },
}
</script>

<template>
  <modal-dialog id="orderFiltersModal" title="Фильтрация заказов">
    <div class="row gy-3">

      <div class="col-md-12">
        <order-searcher-x @on-search-result-select="onCustomerSelect" />
      </div>

      <div class="col-md-3">
        <label class="form-label">Курьеры</label>
        <multiselect placeholder="Выберите курьера..." :options="couriers" v-model="filter_couriers"/>
      </div>
      <div class="col-md-3">
        <label class="form-label">Статусы</label>
        <multiselect placeholder="Выберите статусы заказов..." :options="order_statuses"
                     v-model="filter_order_statuses"/>
      </div>

      <div class="col-md-3">
        <label class="form-label">Откуда</label>
        <multiselect placeholder="Выберите адрес..." :options="addresses" v-model="filter_addresses_from"/>
      </div>
      <div class="col-md-3">
        <label class="form-label">Куда</label>
        <multiselect placeholder="Выберите адрес..." :options="addresses" v-model="filter_addresses_to"/>
      </div>
      <div class="col-md-3">
        <label class="form-label">Дата создания от</label>
        <input type="datetime-local" class="form-control form-control-sm" v-model="filter_start_datetime"/>
      </div>
      <div class="col-md-3">
        <label class="form-label">Дата создания до</label>
        <input type="datetime-local" class="form-control form-control-sm" v-model="filter_end_datetime"/>
      </div>
      <div class="col-md-3">
        <label class="form-label">Города клиентов</label>
        <multiselect placeholder="Выберите города..." :options="cities" v-model="filter_customer_cities"/>
      </div>
    </div>

    <div class="col-md-12 my-3">
      <label class="form-label" for="sort">Сортировка</label>
      <select class="form-select form-select-sm" @change="onOrdersSort" v-model="selected_sort_option" id="sort">
        <option disabled value="">Сортировать по:</option>
        <option :value="option.sort_order" v-for="option in orderSortOptions" :key="option.sort_order">
          {{ option.value }}
        </option>
      </select>
    </div>

    <template #footer>
      <input type="button" value="Очистить фильтр" class="btn btn-sm btn-warning" @click="onFilterClear"/>
      <input type="button" value="Применить фильтр" class="btn btn-sm btn-success" data-bs-dismiss="modal"
             @click="onFilter"/>
    </template>

  </modal-dialog >
</template>

<style scoped>

</style>