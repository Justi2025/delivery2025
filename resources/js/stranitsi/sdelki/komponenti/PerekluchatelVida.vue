
<script>
export default {
  name: "ViewTypeSwitcher",

  props: {
    modelValue: {
      type: String,
      required: true
    }
  },

  emits: ['update:modelValue'],

  computed: {
    view_type: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit('update:modelValue', value);
      }
    }
  }
}
</script>

<template>
  <div class="d-flex gap-2">
    <div class="form-check">
      <input class="form-check-input" type="radio" name="view_type" id="viewTypeList" value="list" v-model="view_type">
      <label class="form-check-label cursor-pointer" for="viewTypeList">
        Список
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="view_type" id="viewTypeCard" value="card" v-model="view_type">
      <label class="form-check-label cursor-pointer" for="viewTypeCard">
        Карточки
      </label>
    </div>
  </div>
</template>

<style scoped>

</style>