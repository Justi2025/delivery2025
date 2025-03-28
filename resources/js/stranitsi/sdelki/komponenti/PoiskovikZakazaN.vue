
<script>
import debounce from "../../../utils/debounce.js";
import {servicSdelok} from "../../../api/sdelki/SdelkiServis.js";
import OptsiiSdelok from "../../../api/sdelki/OptsiiSdelok.js";
import {OrderStatus} from "../../../utils/zakazi/statusi_zakaza.js";
import tipPoiskaSdelki from "../obschie/TipPoiskaSdelki.js";
import {mapActions, mapGetters} from "vuex";

export default {
  name: "OrdersSearcher",

  emits: ['onSearchCompleted', 'onError', 'onSearchClear', 'onLoading'],

  props: {
    orderStatuses: {
      type: Array,
      required: false,
      default: []
    },

    refElement: {
      type: String,
      required: false
    },

    storeId: {
      type: String,
      required: true
    }
  },

  data: () => ({
    onSearchDebounced: null,
    orders: [],
    selected_customer: null,

    is_loading: false,
  }),

  created() {
    this.onSearchDebounced = debounce(this.onSearch, 500);
  },

  async mounted() {
    document.addEventListener('keydown', this.clearSearchInput);
  },

  beforeUnmount() {
    document.removeEventListener('keydown', this.clearSearchInput);
  },

  computed: {
    ...mapGetters('orderSearcher', ['getSearchParams']),

    search_params() {
      return this.getSearchParams(this.storeId);
    },

    search_term: {
      get() {
        return this.search_params?.term;
      },
      set(value) {
        this.setSearchTerm(value);
      }
    },

    search_type: {
      get() {
        return this.search_params?.type || this.search_type_id;
      },
      set(value) {
        this.setSearchType(value);
      }
    },

    placeholder() {
      return this.search_type === tipPoiskaSdelki.ClientName ? 'Введите ФИО клиента...' : 'Введите номер заказа...';
    },

    search_type_id() {
      return this.is_admin_or_manager ? tipPoiskaSdelki.ClientName : tipPoiskaSdelki.OrderNumber;
    }
  },

  methods: {
    ...mapActions('orderSearcher', ['setSearchParams', 'clearSearchParams']),


    setSearchTerm(term) {
      this.setSearchParams({ id: this.storeId, params: { term } });
    },

    setSearchType(type) {
      this.setSearchParams({ id: this.storeId, params: { type } });
    },

    async onSearch(e) {
      const searchTerm = e.target.value?.trim();

      if (searchTerm.length === 0) {
        this.clearSearchResult();
        return;
      }

      if (this.selected_customer && (this.selected_customer.full_name === this.$refs.search_input.value)) return;

      if(this.search_type === tipPoiskaSdelki.ClientName) {
        const data = await this.loadOrders({ customer_name: this.search_term });
        this.$emit('onSearchCompleted', data);
      }
      else {
        const data = await this.loadOrders({ order_id: Number(this.search_term) });
        this.$emit('onSearchCompleted', data);
      }
    },

    clearSearchInput(e) {
      if (e.key === 'Delete' || e.key === 'Delete') {
        this.clearSearchResult();
        this.$refs.search_input.blur();
      }
    },

    clearSearchResult() {
      this.$refs.search_input.value = '';
      this.orders = [];
      this.selected_customer = null;
      this.search_term = '';

      this.$emit('onSearchClear');
    },


    async loadOrders({ customer_name, order_id }) {
      let _data = {};
      const opts = new OptsiiSdelok();
      opts.query = this.$route.query;
      opts.filters.order_statuses = [...this.orderStatuses];
      opts.filters.customer_name = customer_name;
      opts.filters.order_id = order_id;

      try {
        this.is_loading = true;
        this.$emit('onLoading', this.is_loading);

        const {data} = await servicSdelok().index(opts.query, opts.filters, {});
        _data = data;
      }
      catch (e) {
        this.$emit('onError', e);
      }
      finally {
        this.is_loading = false;
        this.$emit('onLoading', this.is_loading);
      }

      return _data;

    }
  }
}
</script>

<template>
  <div class="searcher">
    <label for="searchTerm" class="form-label">Поиск заказа</label>

    <div class="input-group mb-3">
      <input type="text" class="form-control form-control-sm" id="searchTerm" ref="search_input" :placeholder="placeholder"
             aria-label="Введите ФИО" aria-describedby="search-term-selection" v-model="search_term"
             @keyup="onSearchDebounced">

      <div>
        <select class="form-select form-select-sm rounded-0" v-model="search_type" @change="onSearchDebounced">
          <option value="client_name">По ФИО</option>
          <option value="order_no">По номеру</option>
        </select>
      </div>

      <button type="button" class="btn btn-sm btn-outline-warning me-2 input-group-text"
              id="customer-selection-cancel"
              @click="clearSearchResult">Очистить
      </button>
    </div>

  </div>
</template>

<style scoped>
.searcher {
  position: relative;
}

.searcher__results-dropdown {
  position: absolute;
  width: 100%;
  left: 0;
  top: 85px;
  z-index: 1000;
  border: 1px solid var(--bs-secondary-bg-subtle);
  border-radius: var(--bs-border-radius);
}
</style>