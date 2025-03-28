/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<script>
import debounce from "../../../utils/debounce.js";
import {servicePolzovatelei} from "../../../api/index.js";
import OptsiiSdelok from "../../../api/sdelki/OptsiiSdelok.js";
import {servicSdelok} from "../../../api/sdelki/SdelkiServis.js";

export default {
  name: "OrderSearcherX",

  emits: ['onSearchResultSelect'],

  data: () => ({
    onSearchDebounced: null,
    data: [],
    selected_item: null,

    search_type: 'client_name'
  }),

  created() {
    this.onSearchDebounced = debounce(this.onClientSearch, 500);

    this.search_type = this.is_admin_or_manager ? 'client_name' : 'order_no';
  },

  computed: {
    placeholder() {
      return this.search_type === 'client_name' ? 'Введите ФИО клиента...' : 'Введите номер заказа...';
    },
  },

  methods: {

    search_result_title(item) {
      return this.search_type === 'client_name' ? item.full_name : 'Заказ #' + item.id
    },

    async onClientSearch(e) {
      const searchTerm = e.target.value?.trim();

      if (searchTerm.length === 0) {
        this.clearSearchResult();
        return;
      }

      if (this.selected_item &&
          (this.selected_item.full_name === this.$refs.client.value ||
              this.selected_item.id === this.$refs.client.value)) return;

      const opts = new OptsiiSdelok();
      opts.query = this.$route.query;

      if (this.search_type === 'client_name') {
        opts.filters.customer_name = searchTerm;
      } else {
        opts.filters.order_id = Number(searchTerm);
      }

      // Todo: add sub path and remove unnecessary fields from response
      if(this.search_type === 'client_name') {
        const {data} = await servicePolzovatelei().naitiPokupatelia(searchTerm);
        console.log(data);
        this.data = data.data;
      }
      else {
        // Todo:
        const {data} = await servicSdelok().search(opts);
        this.data = data.data;
      }

    },

    clearSearchResult() {
      this.$refs.client.value = '';
      this.data = [];
      this.selected_item = null;
      this.search_type = 'client_name';
    },

    onSearchResultSelect(e) {
      const item_id = +e.target.dataset.itemId;
      this.selected_item = this.data.find(c => c.id === item_id);
      this.$refs.client.value = this.search_result_title(this.selected_item);
      this.data = [];

      this.$emit('onSearchResultSelect', {search_id: item_id, search_type: this.search_type});
    },
  }
}
</script>

<template>
  <div class="col-md-12">
    <div class="searcher">
      <label for="client" class="form-label">Поиск</label>

      <div class="input-group mb-3">
        <input type="text" class="form-control form-control-sm" id="client" ref="client" :placeholder="placeholder"
               aria-label="Введите ФИО" aria-describedby="customer-selection-cancel"
               @keyup="onSearchDebounced">

        <div>
          <select class="form-select form-select-sm rounded-0" v-model="search_type">
            <option value="client_name">По ФИО</option>
            <option value="order_no">По номеру заказа</option>
          </select>
        </div>

        <button type="button" class="btn btn-sm btn-outline-warning me-2 input-group-text"
                id="customer-selection-cancel"
                @click="clearSearchResult">Очистить
        </button>
      </div>

      <ul class="list-group searcher__results-dropdown" v-if="data.length > 0"
          @click="onSearchResultSelect">
        <li class="list-group-item cursor-pointer" v-for="item in data" :key="item.id"
            :data-item-id="item.id">
          {{ search_result_title(item) }}
        </li>
      </ul>
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
  max-height: 300px;
  overflow-x: auto;
}


</style>