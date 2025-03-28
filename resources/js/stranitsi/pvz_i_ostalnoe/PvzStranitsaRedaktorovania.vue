
<template>
  <obertka-adminki>

    <zagolovok-stranitc :caption="$route.meta.caption">
      <router-link class="btn btn-sm btn-outline-warning d-flex" :to="url('pickuppoints')">
        К списку
      </router-link>
    </zagolovok-stranitc>

    <div class="mx-1">

      <div v-show="error_message" class="alert alert-warning">{{ error_message }}</div>

      <form class="row gx-3 gy-4" @submit.prevent="onFormSubmit">

        <div class="col-md-6">
          <label for="company_id" class="form-label required">Компания</label>
          <select id="company_id" class="form-select" ref="company_id" v-model="company_id" required>
            <option value="0" disabled>Выберите компанию</option>
            <option :value="company.id" v-for="company in companies">{{ company.name }}</option>
          </select>
        </div>

        <div class="col-md-2">
          <label for="company_short_name" class="form-label required">Короткое название</label>
          <input type="text" id="company_short_name" class="form-control" ref="company_short_name" required>
        </div>

        <div class="col-md-2">
          <label for="company_color" class="form-label">Цвет брэнда</label>
          <input type="color" id="company_color" class="form-control form-control-color w-100" ref="company_color">
        </div>

        <div class="col-md-2">
          <label for="label_color" class="form-label">Цвет метки</label>
          <input type="color" id="label_color" class="form-control form-control-color w-100" ref="label_color">
        </div>

        <div class="col-md-6">
          <label for="country" class="form-label required">Страна</label>
          <select id="country" class="form-select" ref="country" required>
            <option value="ab">Абхазия</option>
            <option value="ru">Россия</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="city" class="form-label required">Город</label>
          <input type="text" id="city" class="form-control" ref="city" required>
        </div>


        <div class="col-md-6">
          <label for="street" class="form-label required">Улица</label>
          <input type="text" class="form-control" id="street" ref="street">
        </div>

        <div class="col-md-6">
          <label for="house" class="form-label required">Номер строения</label>
          <input type="text" class="form-control" id="house" ref="house" required>
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
          <input type="submit" value="Сохранить" class="btn btn-warning w-100">
        </div>

      </form>
    </div>

  </obertka-adminki>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import {deliveryPointsService} from "../../api/ServisPvzs.js";
import {ref_value, ref_value_set} from "../../utils/index.js";
import {companiesService} from "../../api/ServisKompanii.js";
import {mapMutations} from "vuex";

export default {
  name: "DeliveryPointEditPage",
  components: {ObertkaAdminki, ZagolovokStranitc},

  data: () => ({
    point_data: [],
    error_message: '',
    companies: [],

    company_id: 0,
  }),

  async mounted() {
    await this.loadAndFillForm();
    await this.loadCompanies();
  },

  methods: {

    ...mapMutations(['UPDATE_ADDRESS']),

    async loadCompanies() {
      const {data} = await companiesService().spisokPoluchit();
      this.companies = data.data;
    },

    async loadAndFillForm() {

      deliveryPointsService()
          .get(this.$route.params.id)
          .then(response => {
            this.fillForm(response.data);
          })
          .catch((e) => {
            const status = e?.response?.status || 0;

            if (404 === status) {
              this.redirect_to('pickuppoints');
            } else {
              this.error_message = e?.response?.data?.message;
            }
          });
    },

    async onFormSubmit() {
      const value = ref_value(this);

      const data = {
        country: value('country'),
        city: value('city'),
        street: value('street'),
        house: value('house'),
        company_id: value('company_id'),
        short_name: value('company_short_name'),
        color: value('company_color'),
        label_color: value('label_color'),
        comment: value('comment'),
        is_barcode_changing: value('is_barcode_changing') ? 1 : 0,
        additional_payment: value('additional_payment')
      };

      deliveryPointsService()
          .updatePoint(this.$route.params.id, data)
          .then(response => {
            if (200 === response.status) {
              this.UPDATE_ADDRESS(response.data);
              alert('Данные обновлены!');
              this.redirect_to('pickuppoints');
            } else {
              alert('Ошибка при обновлении данных пункта выдачи');
            }
          })
          .catch(e => {
            this.error_message = e?.response?.message || e?.response?.data?.message;
          });
    },


    fillForm(data) {
      const value = ref_value_set(this);

      this.company_id = data.company_id;

      value('country', data.country);
      value('city', data.city);
      value('street', data.street);
      value('house', data.house);
      //value('company_id', data.company_id);
      value('company_short_name', data.short_name);
      value('company_color', data.color);
      value('label_color', data.label_color);
      value('comment', data.comment);
      value('is_barcode_changing', data.is_barcode_changing);
      value('additional_payment', data.additional_payment);
    }
  },
}
</script>

<style scoped>

</style>