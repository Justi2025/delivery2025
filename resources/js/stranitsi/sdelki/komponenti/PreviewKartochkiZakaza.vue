/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<script>
import {getStatusById, orderStatusTranslate} from "../../../utils/zakazi/statusi_zakaza.js";

export default {
  name: "OrderPreviewCard",

  emits: ['onOrderAccept'],

  props: {
    order: {
      type: Object,
      required: true
    },
    has_accept_button: {
      type: Boolean,
      required: false
    }
  },

  methods: {
    getStatusById,
    orderStatusTranslate,

    onOrderAccept(e) {
      this.$emit('onOrderAccept', e);
    }
  }
}
</script>

<template>
  <div draggable="true" class="card h-100 border" :class="'border-' + getStatusById(order?.order_status).clazz">
    <div class="card-header ps-2">
      <span :class="'badge bg-' + getStatusById(order?.order_status).clazz">
            {{ orderStatusTranslate(order?.order_status) }}
            </span>
    </div>
    <img :src="upload_path(order?.barcode_image)" class="card-img-top" :class="{'img-blurred': is_courier }"
         alt="order barcode"
         v-if="order?.barcode_image">
    <div class="card-body d-flex flex-column">
      <div class="mt-auto">
        <h5 class="card-title">Заказ #{{ order?.id }}</h5>
        <p class="card-text">
          <span>{{  }}</span>
        </p>
        <router-link :to="route_url('dostavka.view', { id: order.id }).fullPath"
                     class="btn btn-sm " :class="'btn-outline-' + getStatusById(order?.order_status).clazz">
          Открыть
        </router-link>
        <button v-if="has_accept_button" class="ms-2 btn btn-sm btn-outline-success" :data-order-id="order?.id" @click="onOrderAccept">
          Принять
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.card-img-top {
  cursor: pointer;
  max-height: 200px;
  object-fit: contain;
}

.card-img-top:hover {
  filter: blur(0);
}

.img-blurred {
  filter: blur(5px);
  transition: filter .3s ease-in;
}

</style>