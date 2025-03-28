/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <div>
    <zagolovok-stranitc caption="Новая компания">
      <button class="btn btn-sm btn-outline-warning d-flex" @click="closeForm">
        Закрыть
      </button>
    </zagolovok-stranitc>
    <div class="mx-1">

      <div v-show="error" class="alert alert-warning">{{ error }}</div>

      <form class="row gx-3 gy-4" @submit.prevent="onFormSubmit">

        <div class="col-md-12">
          <label for="company_name" class="form-label required">Компания</label>
          <input type="text" id="company_name" class="form-control" ref="company_name" required>
        </div>

        <div class="col-md-12">
          <label for="country" class="form-label required">Страна</label>
          <select id="country" class="form-select" ref="country" required>
            <option value="0" selected disabled>Выберите страну</option>
            <option value="ab">Абхазия</option>
            <option value="ru">Россия</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="company_color" class="form-label">Цвет брэнда</label>
          <input type="color" id="company_color" class="form-control form-control-color w-100" ref="company_color">
        </div>

        <div class="col-md-6">
          <label for="label_color" class="form-label">Цвет метки</label>
          <input type="color" id="label_color" class="form-control form-control-color w-100" ref="label_color">
        </div>

        <div class="col-md-12">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="pickup_only_paid" ref="pickup_only_paid">
            <label class="form-check-label" for="pickup_only_paid">
              Можно забрать только оплаченный заказ
            </label>
          </div>
        </div>

        <div class="col-md-12">
          <input type="submit" value="Добавить компанию" class="btn btn-warning w-100">
        </div>

      </form>
    </div>

  </div>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import {ref_value} from "../../utils/index.js";

export default {
  name: "CompanyCreateForm",
  components: {ZagolovokStranitc},

  emits: ['onFormClose', 'onFormData'],

  props: {
    error: {
      type: String,
      required: false,
    }
  },

  methods: {
    closeForm() {
      this.$emit('onFormClose');
    },

    onFormSubmit() {
      const value = ref_value(this);

      const data = {
        name: value('company_name'),
        country: value('country'),
        company_color: value('company_color'),
        label_color: value('label_color'),
        pickup_only_paid: value('pickup_only_paid') ? 1 : 0,
      };

      this.$emit('onFormData', data);
    }
  }
}
</script>

<style scoped>

</style>