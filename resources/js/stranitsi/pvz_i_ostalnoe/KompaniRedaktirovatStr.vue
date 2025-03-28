
<template>
  <obertka-adminki>

    <zagolovok-stranitc :caption="$route.meta.caption">
      <router-link class="btn btn-sm btn-outline-warning d-flex" :to="url('companies')">
        К списку
      </router-link>
    </zagolovok-stranitc>

    <div class="mx-1">

      <div v-show="error_message" class="alert alert-warning">{{ error_message }}</div>

      <form class="row gx-3 gy-4" @submit.prevent="pri_sokranenii_formi">

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

export default {
  name: "CompanyEditPage",
  components: {ObertkaAdminki, ZagolovokStranitc},

  data: () => ({
    point_data: [],
    error_message: '',
  }),

  async mounted() {
    await this.loadAndFillForm();
  },

  methods: {
    async loadAndFillForm() {

      companiesService()
          .get(this.$route.params.id)
          .then(response => {
            this.zapolnit_formu(response.data);
          })
          .catch((e) => {
            const status = e?.response?.status || 0;

            if (404 === status) {
              this.redirect_to('companies');
            } else {
              this.error_message = e?.response?.data?.message;
            }
          });
    },

    async pri_sokranenii_formi() {
      const value = ref_value(this);

      const data = {
        name: value('company_name'),
        country: value('country'),
        company_color: value('company_color'),
        label_color: value('label_color'),
        pickup_only_paid: value('pickup_only_paid') ? 1 : 0,
      };

      companiesService()
          .updateMarketplace(this.$route.params.id, data)
          .then(response => {
            if(200 === response.status) {
              alert('Данные обновлены');
              this.redirect_to('companies');
            }
            else {
              alert('Ошибка при обновлении');
            }
          })
          .catch(e => {
            this.error_message =  e?.response?.message || e?.response?.data?.message;
          });
    },


    zapolnit_formu(data) {
      const value = ref_value_set(this);
      value('company_name', data.name);
      value('country', data.country);
      value('company_color', data.company_color);
      value('label_color', data.label_color);
      value('pickup_only_paid', data.pickup_only_paid);
    }
  },
}
</script>

<style scoped>

</style>