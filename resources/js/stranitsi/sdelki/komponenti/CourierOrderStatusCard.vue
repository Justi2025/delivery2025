
<script>
import {nl2br} from "../../../utils/nl2br.js";

export default {
  name: "CourierOrderStatusCard",

  emits: ['onPhotoClick'],

  props: {
    item: {
      type: Object,
      required: true,
      default: {}
    }
  },

  methods: {
    nl2br,
    onOrderHistoryPhotoClick(e) {
      this.$emit('onPhotoClick', e);
    }

  }
}
</script>

<template>
  <div class="card my-3 position-relative" v-if="item">

    <div class="card-body px-3">

      <p class="text-secondary mb-1">Комментарий:</p>
      <p v-html="nl2br(item?.comment)"></p>

      <div class="row my-3" v-if="item?.files?.length > 0">
        <div class="col" @click="onOrderHistoryPhotoClick">
          <img class="img-fluid img-thumbnail barcode-image me-2"
               alt="Фото заказа"
               data-bs-toggle="modal"
               data-bs-target="#orderPhotoModal"
               :data-image="upload_path(file.generated_name)"
               v-for="file in item?.files"
               :src="upload_path(file.generated_name)"
               :key="file.generated_name"
          />
        </div>

      </div>

    </div>

  </div>
</template>

<style scoped>
.barcode-image {
  width: 64px;
  cursor: pointer;
}
svg {
  top: -10px;
  right: -5px;
  width: 22px;
  height: 22px;
}
</style>