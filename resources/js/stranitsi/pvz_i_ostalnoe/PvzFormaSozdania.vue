/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <div>
    <zagolovok-stranitc caption="Новый ПВЗ">
      <button class="btn btn-sm btn-outline-warning d-flex" @click="closeForm">
        Закрыть
      </button>
    </zagolovok-stranitc>
    <div class="mx-1">

      <div v-show="error" class="alert alert-warning">{{ error }}</div>

      <form class="row gx-3 gy-4" @submit.prevent="onFormSubmit">

        <div class="col-md-6">
          <label for="company_id" class="form-label required">Компания</label>
          <select id="company_id" class="form-select" ref="company_id" required v-model="company_id">
            <option value="" selected disabled>Выберите компанию</option>
            <option :value="company.id" v-for="company in companies">{{ company.name }}</option>
          </select>
        </div>

        <div class="col-md-2">
          <label for="company_short_name" class="form-label required">Короткое название</label>
          <input type="text" id="company_short_name" class="form-control" ref="company_short_name" required>
        </div>

        <div class="col-md-2">
          <label for="company_color" class="form-label">Цвет брэнда</label>
          <input type="color" id="company_color" class="form-control form-control-color w-100" ref="company_color"
                 v-model="company_color">
        </div>

        <div class="col-md-2">
          <label for="label_color" class="form-label">Цвет метки</label>
          <input type="color" id="label_color" class="form-control form-control-color w-100" ref="label_color"
                 v-model="company_label_color">
        </div>

        <div class="col-md-6">
          <label for="country" class="form-label required">Страна</label>
          <select id="country" class="form-select" ref="country" required v-model="company_country">
            <option value="ab">Абхазия</option>
            <option value="ru">Россия</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="city" class="form-label required">Город</label>
          <input type="text" id="city" class="form-control" ref="city" autocomplete="on" required>
        </div>


        <div class="col-md-6">
          <label for="street" class="form-label required">Улица</label>
          <input type="text" class="form-control" id="street" ref="street">
        </div>

        <div class="col-md-6">
          <label for="house" class="form-label required">Номер строения</label>
          <input type="text" class="form-control" id="house" ref="house">
        </div>

        <div class="col-md-12">
          <label for="additional_payment" class="form-label">Доплата за пункт</label>
          <input type="number" min="0" id="additional_payment" class="form-control" ref="additional_payment">
        </div>

        <div class="col-md-12">
          <label for="comment" class="form-label">Комментарий</label>
          <textarea id="comment" cols="30" rows="10" class="form-control" ref="comment"></textarea>
        </div>

        <div class="col-12">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="is_barcode_changing" ref="is_barcode_changing">
            <label class="form-check-label" for="is_barcode_changing">
              Штрих-код меняется
            </label>
          </div>
        </div>

        <div class="col">
          <input type="submit" value="Добавить ПВЗ или Офис" class="btn btn-warning w-100">
        </div>

      </form>
    </div>

  </div>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import {ref_value} from "../../utils/index.js";
import {companiesService} from "../../api/ServisKompanii.js";

export default {
  name: "DeliveryPointCreateForm",
  components: {ZagolovokStranitc},

  emits: ['onFormClose', 'onFormData'],

  props: {
    error: {
      type: String,
      required: false,
    }
  },

  data: () => ({
    companies: [],
    company_id: ''
  }),

  async mounted() {
    const {data} = await companiesService().spisokPoluchit();
    this.companies = data.data;
  },

  computed: {
    company_country() {
      const company = this.company_id ? this.companies.find(cmp => cmp.id === this.company_id) : '';
      return company ? company.country : 'ru';
    },

    company_color() {
      const company = this.company_id ? this.companies.find(cmp => cmp.id === this.company_id) : '';
      return company ? company.company_color : '#000';
    },

    company_label_color() {
      const company = this.company_id ? this.companies.find(cmp => cmp.id === this.company_id) : '';
      return company ? company.label_color : '#fff';
    },
  },

  methods: {
    closeForm() {
      this.$emit('onFormClose');
    },

    onFormSubmit() {
      const value = ref_value(this);

      const data = {
        country: value('country'),
        city: value('city'),
        street: value('street'),
        house: value('house'),
        name: value('company_name'),
        company_id: value('company_id'),
        short_name: value('company_short_name'),
        color: value('company_color'),
        label_color: value('label_color'),
        comment: value('comment'),
        is_barcode_changing: value('is_barcode_changing') ? 1 : 0,
        additional_payment: value('additional_payment')
      };

      this.$emit('onFormData', data);
    }
  }
}
</script>

<style scoped>

</style>