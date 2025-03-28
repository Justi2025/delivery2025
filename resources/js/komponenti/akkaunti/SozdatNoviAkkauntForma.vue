
<template>
  <div class="mx-1">

    <div v-show="error" class="alert alert-warning">{{ error }}</div>

    <form class="row gx-3 gy-4" @submit.prevent="onFormSubmit">

      <div class="col-md-3">
        <label for="lastname" class="form-label">Фамилия</label>
        <input type="text" class="form-control" id="lastname" ref="lastname">
      </div>
      <div class="col-md-2">
        <label for="firstname" class="form-label">Имя</label>
        <input type="text" class="form-control" id="firstname" ref="firstname">
      </div>
      <div class="col-md-3">
        <label for="middlename" class="form-label">Отчество</label>
        <input type="text" class="form-control" id="middlename" ref="middlename">
      </div>

      <div class="col-md-2">
        <label for="year_of_birth" class="form-label">Год рождения</label>
        <select class="form-select" id="year_of_birth" ref="year_of_birth">
          <option value="0">Выберите год</option>
          <option :value="year" v-for="year in range(1950, 2024)" :key="year">{{ year }}</option>
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label required" for="citySelect">Город</label>
        <!-- Todo: maybe this is twice define same block? -->
        <select id="citySelect" class="form-select" ref="city" required :value="formData.city" v-if="is_edit_mode">
          <option value="" disabled>Выберите город</option>
          <option :value="city.id" v-for="city in cities" :key="city.id">{{ city.name }}</option>
        </select>
        <select id="citySelect" class="form-select" ref="city" required v-else>
          <option value="" disabled>Выберите город</option>
          <option :value="city.id" v-for="city in cities" :key="city.id">{{ city.name }}</option>
        </select>
      </div>

      <!--<div :class="{'col-md-6': !is_edit_mode, 'col-md-12': is_edit_mode}">-->
      <div class="col-md-6">
        <label for="phone" class="form-label">Номер телефона</label>
        <input
            id="phone" ref="phone"
            class="form-control"
            minlength="7"
            maxlength="20"
            pattern="^\+7\s\d{3}\s\d{3}-\d{2}-\d{2}$"
            required
            placeholder="+7 999 123-45-67"
            type="tel"
            v-maska data-maska="+7 ### ###-##-##"
        >
      </div>
      <!--<div class="col-md-6" v-if="!is_edit_mode">-->
      <div class="col-md-6">
        <label for="password" class="form-label">Пароль</label>
        <input type="text" class="form-control" id="password" ref="password">
      </div>

      <div class="col-md-4">
        <label for="street" class="form-label">Улица</label>
        <input type="text" class="form-control" id="street" placeholder="Название Вашей улицы" ref="street">
      </div>
      <div class="col-md-4">
        <label for="house" class="form-label">Номер дома</label>
        <input type="text" class="form-control" id="house" placeholder="Введите номер дома" ref="house">
      </div>
      <div class="col-md-4">
        <label for="flat" class="form-label">Квартира</label>
        <input type="text" class="form-control" id="flat" placeholder="Номер квартиры (если квартира)" ref="flat">
      </div>

      <div class="col-12">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="is_vip" ref="is_vip">
          <label class="form-check-label" for="is_vip">
            ВИП Клиент
          </label>
        </div>
      </div>

      <div class="col-md-12">
        <div class="mb-2">
          <file-chooser caption="Фото паспорта" class="btn btn-sm btn-outline-warning" accept="image/*" @on-file-choose="onPassportChoose" />
        </div>
        <img :src="upload_path(formData.passport_image)" alt="passport image" class="w-25 rounded-2 me-2" v-if="formData?.passport_image">
        <img :src="passportImage" alt="passport image" class="w-25 rounded-2" v-if="passportImage">
      </div>

      <div class="col-12" id="actionButtons">
        <!-- Todo: refactor to separate component -->
        <button class="btn btn-sm btn-success d-flex align-items-center" type="button" disabled v-if="updating">
          <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
          <span role="status">
             {{ is_edit_mode ? 'Обновление...' : 'Создание...' }}
          </span>
        </button>

        <button type="submit" class="btn btn-success btn-sm" v-else>
          {{ is_edit_mode ? 'Обновить запись' : 'Создать аккаунт' }}
        </button>

      </div>

    </form>
  </div>
</template>

<script>
import ZagolovokStranitc from "../ZagolovokStranitc.vue";
import FileChooser from "../ViborFaila.vue";
import {ref_value, ref_value_set} from "../../utils/index.js";
import {citiesService} from "../../api/index.js";
import {objiect_pustoi} from "../../utils/objiect_pustoi.js";

export default {
  name: "NewAccountForm",
  components: {FileChooser, ZagolovokStranitc},

  props: {
    error: {
      type: String,
      required: false
    },
    moveActionButtonsTo: {
      type: String,
      required: false,
      default: '#actionButtons'
    },

    formData: {
      type: Object,
      required: false,
      default: {}
    },

    updating: {
      type: Boolean,
      required: false,
      default: false,
    }
  },

  emits: ['onFormClose', 'onData'],

  data: () => ({
      passportImage: null,
      passportImageFile: null,
      cities: [],
  }),

  async mounted() {
    this.cities = await citiesService().poluchitGoroda();

    if(this.formData) {
      this.fillForm(this.formData);
    }
  },

  computed: {
    is_edit_mode() {
      return !objiect_pustoi(this.formData);
    }
  },

  methods: {
    isEmpty: objiect_pustoi,

    closeForm() {
      this.$emit('onFormClose');
    },

    onPassportChoose(file) {
      const fileReader = new FileReader();
      fileReader.onload = () => {
        this.passportImage = fileReader.result;
      }
      fileReader.readAsDataURL(file);

      this.passportImageFile = file;
    },

    onFormSubmit() {

      const value = ref_value(this);

      const data = {
          full_name: `${value('lastname')} ${value('firstname')} ${value('middlename')}`,
          year_of_birth: value('year_of_birth'),
          city_id: value('city'),
          phone: String(value('phone')).replace(/\D/g,''),
          password: value('password'),
          street: value('street'),
          house: value('house'),
          flat: value('flat'),
          is_vip: value('is_vip') ? 1 : 0,
      };

      if(this.passportImageFile) {
        data['passport_image'] = this.passportImageFile;
      }


      this.$emit('onData', data);
    },

    fillForm(data) {
      const value_set = ref_value_set(this);

      for (const prop in data) {
        value_set(prop, data[prop] !== undefined ? data[prop] : '');
      }
    }
  }
}
</script>

<style scoped>

</style>