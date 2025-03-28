
<script>
import Slider from "../../../komponenti/Slider.vue";

export default {
  name: "ImageSliderModal",
  components: {Slider},

  props: {
    id: {
      type: String,
      required: true
    },
    images: {
      type: Array,
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
          <h5 class="modal-title">Штрих-код(ы)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body d-flex justify-content-center">
          <slider :images="images" />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>