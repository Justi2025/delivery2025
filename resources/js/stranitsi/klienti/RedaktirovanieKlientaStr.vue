/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>
    <zagolovok-stranitc :caption="$route.meta.caption">
      <router-link class="btn btn-sm btn-outline-warning" :to="url('customers')">
        К списку
      </router-link>
    </zagolovok-stranitc>

    <new-account-form :form-data="customer_data" :error="error" @on-data="onCustomerData" v-if="customer_data"
                      :updating="loading"/>

  </obertka-adminki>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import NewAccountForm from "../../komponenti/akkaunti/SozdatNoviAkkauntForma.vue";
import {servicePolzovatelei} from "../../api/index.js";
import {ref_value_set} from "../../utils/index.js";
import {deliveryPointsService} from "../../api/ServisPvzs.js";

export default {
  name: "CustomerEditPage",
  components: {NewAccountForm, ObertkaAdminki, ZagolovokStranitc},

  data: () => ({
    error: '',
    customer_data: null,
    loading: false,
  }),

  async mounted() {
    await this.loadAndFillForm();
  },

  methods: {

    onCustomerData(data) {

      this.switchLoading();
      const customer_id = this.$route.params.id;

      servicePolzovatelei()
          .updateX(customer_id, data)
          .then(response => {
            if (200 === response.status) {
              alert('Профиль успешно отредактирован!');
            }
          })
          .catch(e => {
            this.error = e?.response?.data?.message;
          })
          .finally(() => {
            this.switchLoading();
          });
    },

    transformResponseData(response) {

      if (!response?.data) return null;

      const not_empty = s => s.trim() !== '';
      const [lastname, firstname, middlename] = response?.data?.full_name.split(/\s/).filter(not_empty);

      return {...response.data, ...{lastname, firstname, middlename, city: response.data.city_id}};
    },

    async loadAndFillForm() {

      servicePolzovatelei()
          .getById(this.$route.params.id)
          .then(response => {
            this.customer_data = this.transformResponseData(response);
          })
          .catch((e) => {
            const status = e?.response?.status || 0;

            if (404 === status) {
              this.redirect_to('customers');
            } else {
              this.error_message = e?.response?.data?.message;
            }
          });
    },
  },
}
</script>

<style scoped>

</style>