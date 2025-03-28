/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>

    <template v-if="isFormOpen">
      <company-create-form @on-form-close="toggleForm" @on-form-data="onFormData" :error="error_message"/>
    </template>

    <template v-else>
      <zagolovok-stranitc :caption="$route.meta.caption">
        <button class="btn btn-sm btn-warning d-flex" @click="toggleForm">
          <i class="bi bi-plus-lg me-1"></i>Добавить
        </button>
      </zagolovok-stranitc>

      <div class="mx-1">
        <q-table :cols="cols" :rows="list">
          <template #item="{item}">
            <td>
              <span :style="{backgroundColor: item?.company_color, color: item?.label_color}" class="label">
                {{ item.name }}
              </span>
            </td>
            <td>{{ countryName(item.country) }}</td>
            <td @change="onUsageFrequencyChange">
              <select :value="item?.usage_frequency" class="form-select form-select-sm" name="usage-frequency"
                      :data-company-id="item.id">
                <option v-for="frequency_status in usage_frequency_statuses" :value="frequency_status.value">
                  {{ frequency_status.label }}
                </option>
              </select>
            </td>
            <td>{{ dt_format(item.created_at) }}</td>
            <td>
              <router-link :to="url('companies.edit', { id: item.id })">Редактировать</router-link>
            </td>
          </template>
        </q-table>
      </div>

      <div class="row">
        <pagination :pagination="pagination"/>
      </div>

    </template>
  </obertka-adminki>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import DeliveryPointCreateForm from "./PvzFormaSozdania.vue";
import Pagination from "../../komponenti/Pagination.vue";
import QTable from "../../komponenti/QTable.vue";
import {companiesService} from "../../api/ServisKompanii.js";
import CompanyCreateForm from "./KompaniiSozdatForma.vue";

export default {
  name: "CompaniesPage",
  components: {CompanyCreateForm, QTable, Pagination, DeliveryPointCreateForm, ObertkaAdminki, ZagolovokStranitc},

  data: () => ({
    list: [],
    isFormOpen: false,
    error_message: '',
    pagination: [],

    cols: {
      name: 'Название',
      country: 'Страна',
      usage_frequency: 'Используется',
      created_at: 'Создан',
      actions: 'Действия',
    },

    usage_frequency_statuses: [
      {
        label: 'Обычно',
        value: 2
      },
      {
        label: 'Часто',
        value: 3
      },
      {
        label: 'Очень часто',
        value: 4
      },
      {
        label: 'Редко',
        value: 1
      },
    ]
  }),

  async mounted() {
    await this.getPaginated();
  },

  methods: {
    toggleForm() {
      this.isFormOpen = !this.isFormOpen;
    },

    async getPaginated() {
      const {data} = await companiesService().paginate(this.$route.query);
      this.list = data.data;
      this.pagination = data;
    },

    onFormData(data) {
      companiesService()
          .create(data)
          .then(response => {
            if (201 === response.status) {
              this.list = [...this.list, response.data];
              this.toggleForm();
            }
          })
          .catch(e => {
            this.error_message = e?.response?.data?.message;
          });
    },

    onUsageFrequencyChange(e) {
      const usage_frequency = e.target.value;
      const company_id = e.target.dataset.companyId;

      companiesService()
          .updateMarketplace(company_id, {usage_frequency})
          .then(response => {
            if (200 === response.status) {
            } else {
              alert('Ошибка при обновлении данных');
            }
          })
          .catch(e => {
            this.error_message = e?.response?.message || e?.response?.data?.message;
          });
    },

  },
}
</script>

<style scoped>
.label {
  padding: 5px;
  border-radius: 5px;
}
</style>