
<script>
export default {
  name: "ImageModal",

  props: {
    id: {
      type: String,
      required: true
    },
    image: {
      type: String,
      required: true
    }
  }
}
</script>

<template>
  <div class="modal fade" :id="id" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Штрих-код</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body d-flex justify-content-center">
          <img class="img-fluid"
               alt="Штрихкод заказа"
               :src="image"
               v-if="image"
          />
          <img class="img-fluid" alt="Выбранный штрих-код" :src="image" v-else/>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>