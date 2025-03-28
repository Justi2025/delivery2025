
<template xmlns="http://www.w3.org/1999/html">
  <div>
    <zagolovok-stranitc caption="Новый заказ">
      <button class="btn btn-sm btn-outline-warning d-flex" @click="closeForm">
        Закрыть
      </button>
    </zagolovok-stranitc>
    <div class="mx-1">

      <div class="modal fade" id="orderBarcodeModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Штрих-код заказа</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
              <img class="img-fluid w-50" alt="Выбранное фото заказа" :src="order_barcode"/>
            </div>
          </div>
        </div>
      </div>

      <div v-show="error" class="alert alert-warning">{{ error?.message }}</div>

      <form class="row gx-3 gy-4" @submit.prevent="onFormSubmit" autocomplete="off">

        <div class="col-md-12" v-if="dp_additional_payment">
          <p class="alert alert-success mb-0 d-flex align-items-center">
            <span>
              <bi-info-circle/>
            </span>
            <span class="ms-2">
               Доплата за данный пункт составляет {{ money(dp_additional_payment) }}
            </span>
          </p>
        </div>

        <div class="col-md-12" v-if="is_admin_or_manager">
          <div class="searcher">
            <label for="client" class="form-label">Клиент</label>

            <input type="text" class="form-control" id="client" ref="client" placeholder="Введите ФИО"
                   aria-label="Введите ФИО" aria-describedby="customer-selection-cancel"
                   @keyup="onClientSearchDebounced">

            <div class="mt-2">
              <button type="button" class="btn btn-sm btn-outline-warning me-2" id="customer-selection-cancel"
                      @click="clearSearchResult">Очистить поле
              </button>
              <router-link :to="url('customers', {}, {action: 'customer.add', redirect: 'dostavka'})"
                           class="btn btn-sm btn-outline-success">
                Добавить клиента
              </router-link>
              <!--<button class="btn btn-sm btn-outline-success" type="button"
                data-bs-target="#newCustomerModal" data-bs-toggle="modal">
                Добавить клиента
              </button>-->
            </div>

            <ul class="list-group searcher__results-dropdown" v-if="found_customers.length > 0"
                @click="onCustomerSelect">
              <li class="list-group-item cursor-pointer" v-for="customer in found_customers" :key="customer.id"
                  :data-customer-id="customer.id">
                {{ customer.full_name }}
              </li>
            </ul>
          </div>
        </div>

        <template v-if="is_customer || selected_customer">

          <!-- Shipping from -->
          <div class="col-md-3">
            <label class="form-label required" for="from_id">Компании</label>
            <select id="from_id" class="form-select" required v-model="selected_foreign_delivery_point">
              <option value="0" disabled selected>Выберите компанию</option>
              <option :value="company.id" v-for="company in delivery_points_from" :key="company.id">
                {{ company?.name }}
              </option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label required" for="from_id_address">Адрес ПВЗ</label>
            <select id="from_id_address" class="form-select" ref="from_id" required
                    :disabled="delivery_points_from_addresses.length === 0" @change="onFromIdChange">
              <option value="0" disabled selected>Выберите адрес</option>
              <option :value="dp.id" v-for="dp in delivery_points_from_addresses" :key="dp.id">
                {{ dp.value }}
              </option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label required" for="shipping_to">Куда</label>

            <div class="row gy-3">

              <div class="col-md-4">
                <select id="shipping_to" class="form-select" v-model="selected_destination_type">
                  <option value="" disabled selected>Выберите куда доставить</option>
                  <option class="form-label" v-for="st in destination_types" :value="st.type">
                    {{ st.name }}
                  </option>
                </select>

              </div>
              <div class="col-md-8">

                <select id="to_id" class="form-select" ref="to_id" v-if="isDestinationTypeDeliveryPoint">
                  <option value="" disabled selected>Выберите офис</option>
                  <option :value="dp.id" v-for="dp in delivery_points_to" :key="dp.id">
                    {{ dp.value }}
                  </option>
                </select>

                <template v-if="isDestinationTypeCustomerHome">
                  <input type="text" class="form-control" :value="customer_address" readonly v-if="customer_address"/>
                  <template v-else>
                    <router-link :to="url('customers.edit', { id: selected_customer.id })" class="btn btn-warning"
                                 v-if="is_admin_or_manager">
                      Добавить адрес
                    </router-link>
                    <router-link :to="url('profile')" class="btn btn-warning" v-else>Добавить адрес</router-link>
                  </template>
                </template>

                <input type="text" class="form-control"
                       v-if="isDestinationTypeToAddress" value="Комментарий 👇👇👇" disabled/>

              </div>
            </div>
          </div>

          <div class="col-md-12 mt-4">
            <label for="order_comment" class="form-label">Комментарий</label>
            <textarea class="form-control" cols="30" rows="5" id="order_comment" ref="comment"
                      maxlength="512"></textarea>
          </div>


          <div class="col-md-12">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="order_requires_payment" ref="order_requires_payment"
                     v-model="order_requires_payment">
              <label class="form-check-label cursor-pointer" for="order_requires_payment">
                Нужно оплатить заказ
              </label>
            </div>

            <input
                v-if="order_requires_payment"
                type="number" class="form-control mt-3" id="amount_to_pay_for_customer"
                ref="amount_to_pay_for_customer" placeholder="Введите сумму, которую необходимо оплатить"
                aria-label="Введите сумму, которую необходимо оплатить" aria-describedby="amount-to-pay-for-customer">
          </div>

          <div class="col-md-12">
            <label for="barcode_text" class="form-label">Код посылки (если требуется)</label>
            <input type="text" class="form-control" id="barcode_text" ref="barcode_text"
                   placeholder="Введите код посылки"/>
          </div>

          <div class="col-md-12 mt-4" v-if="false">
            <div class="mb-2">
              <file-chooser id="choose_barcode" caption="Добавить штрих-код"
                            class="btn btn-sm btn-outline-warning"
                            accept="image/*"
                            @on-file-choose="onBarcodeImageSelected"/>
            </div>
            <img :src="barcode_image" alt="Изображение штрих-кода" class="w-25 rounded-2" v-if="barcode_image">
          </div>

          <div class="col-md-12 mt-4">
            <div class="row mt-2 mb-4" v-if="order_barcodes_data?.length > 0">
              <div class="col me-3" @click="onOrderPhotoClick">
                <div class="d-inline-block position-relative me-2"
                     v-for="(image, counter) in order_barcodes_data" :data-name="image.name"
                >
                  <remove-icon class="text-danger cursor-pointer position-absolute" @click="onOrderBarcodeRemove"/>
                  <img class="img-fluid img-thumbnail barcode-image me-2"
                       alt="Изображение штрих-кода"
                       data-bs-toggle="modal"
                       data-bs-target="#orderBarcodeModal"
                       :data-name="image.name"
                       :src="image.data"
                       :key="counter"
                  />
                </div>
              </div>
            </div>
            <div class="row g-3 justify-content-between">
              <div class="col-sm-12 col-md-auto">
                <file-chooser
                    class="btn btn-sm btn-outline-warning"
                    id="choose_barcode" caption="Добавить штрих-код(ы)"
                    accept="image/*"
                    @on-file-choose="onOrderBarcodeSelected"
                />
              </div>
            </div>
          </div>

          <div class="col-md-12" v-if="selected_company?.pickup_only_paid">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="pickup_only_paid" ref="pickup_only_paid">
              <label class="form-check-label cursor-pointer" for="pickup_only_paid">
                Забрать только оплаченный товар
              </label>
            </div>
          </div>

          <div class="col-md-12">
            <input type="submit" value="Добавить заказ" class="btn btn-warning w-100" :disabled="loading">
          </div>

        </template>

      </form>
    </div>

  </div>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import debounce from "../../utils/debounce.js";
import {ref_value} from "../../utils/index.js";
import {authService, servicePolzovatelei} from "../../api/index.js";
import {DestinationType} from "../../utils/DestinationType.js";
import FileChooser from "../../komponenti/ViborFaila.vue";
import prochitaFailKakUrlDannie from "../../utils/readAsDataUrl.js";
import ModalDialog from "../../komponenti/ModalDialog.vue";
import NewAccountForm from "../../komponenti/akkaunti/SozdatNoviAkkauntForma.vue";
import {mapState} from "vuex";
import {pngToJpeg} from "../../utils/failovie_raznie.js";
import {izmenitRazmerIzobrajenia} from "../../utils/resizeImage.js";
import BiInfoCircle from "../../komponenti/ikonki/BiInfoCircle.vue";
import RemoveIcon from "../../komponenti/ikonki/RemoveIcon.vue";

export default {
  name: "OrderCreationForm",
  components: {RemoveIcon, BiInfoCircle, NewAccountForm, ModalDialog, FileChooser, ZagolovokStranitc},

  emits: ['onFormClose', 'onFormData', 'onSearchInputClear'],

  props: {
    error: {
      type: Object,
      required: false,
    },

    loading: {
      type: Boolean,
      required: false
    }
  },

  data: () => ({
    onClientSearchDebounced: null,
    found_customers: [],
    selected_customer: null,

    destination_types: [
      {type: DestinationType.DeliveryPoint, name: 'В офис'},
      {type: DestinationType.CustomerHome, name: 'На дом'},
      {type: DestinationType.ToAddress, name: 'По договоренности'},
    ],

    selected_destination_type: DestinationType.DeliveryPoint,
    barcode_image: null,
    barcode_image_file: null,

    barcode_text: '',
    form_submitted: false,
    selected_foreign_delivery_point: 0,

    dp_additional_payment: null,
    order_requires_payment: false,

    order_barcodes_data: [],
    order_barcodes: [],
    order_barcode: '',
  }),

  created() {
    this.onClientSearchDebounced = debounce(this.onClientSearch, 500);
  },

  async mounted() {
  },

  computed: {
    ...mapState(['addresses', 'couriers', 'companies', 'authenticated_user']),

    selected_company() {
      return this.addresses.find(dp => dp.company_id === +this.selected_foreign_delivery_point)?.company;
    },

    delivery_points_from() {
      return this.companies.filter(c => c.country === 'ru').sort((c1, c2) => c2.usage_frequency - c1.usage_frequency);
    },

    delivery_points_from_addresses() {

      const sortFunc = (d1, d2) => {
        return d2.usage_frequency - d1.usage_frequency
      }

      return this.addresses.filter(dp => {
        return dp.company_id === this.selected_foreign_delivery_point && dp.country === 'ru'
      }).sort(sortFunc);
    },

    delivery_points_to() {
      return this.addresses.filter(dp => dp.country === 'ab');
    },

    isDestinationTypeDeliveryPoint() {
      return this.selected_destination_type === DestinationType.DeliveryPoint;
    },

    isDestinationTypeCustomerHome() {
      return this.selected_destination_type === DestinationType.CustomerHome;
    },

    isDestinationTypeToAddress() {
      return this.selected_destination_type === DestinationType.ToAddress;
    },

    customer_address() {

      if (this.selected_customer) {
        const sc = this.selected_customer;

        // Todo: maybe flat unnecessary
        if (!sc.street || !sc.house || !sc.flat) {
          return '';
        }

        return `${sc.city}, ${sc.street} ${sc.house}, ${sc.flat}`;
      }

      const user = this.authenticated_user;
      if (!user.street || !user.house || !user.flat) {
        return '';
      }

      return `${user.city.name}, ${user.street} ${user.house}, ${user.flat}`;
    },
  },

  methods: {

    async onOrderBarcodeSelected(file) {

      prochitaFailKakUrlDannie(file)
          .then(async (result) => {

            this.order_barcodes_data = [...this.order_barcodes_data, {name: file.name, data: result}];

            const blob = await pngToJpeg(file);
            const image = new File([blob], file.name, {type: 'image/jpeg'});
            this.order_barcodes = [...this.order_barcodes, {name: file.name, data: image}];
          })
          .catch((error) => {
            alert(error);
          });
    },

    onOrderPhotoClick(e) {
      if (!(e.target instanceof HTMLImageElement)) return;
      const image = this.order_barcodes_data.find(image => image.name === e.target.dataset.name);

      if (image) {
        this.order_barcode = image.data;
      }
    },

    onOrderBarcodeRemove(e) {
      const image_name = e.target.closest('div').dataset.name;

      if (image_name) {
        this.order_barcodes = this.order_barcodes.filter(image => image.name !== image_name);
        this.order_barcodes_data = this.order_barcodes_data.filter(image => image.name !== image_name);
      }
    },

    closeForm() {
      this.$emit('onFormClose');
    },

    onFromIdChange(e) {
      const from_id = +e.target.value;
      const selected_from_dp = this.delivery_points_from_addresses.find(dp => dp.id === from_id);
      this.dp_additional_payment = selected_from_dp?.additional_payment;
    },

    async getAuthenticatedUser() {
      const {data} = await authService().getUser();
      return data;
    },

    async onClientSearch(e) {
      const searchTerm = e.target.value?.trim();

      if (searchTerm.length === 0) {
        this.clearSearchResult();
        return;
      }
      if (searchTerm.length < 3) return;
      if (this.selected_customer && (this.selected_customer.full_name === this.$refs.client.value)) return;

      const {data} = await servicePolzovatelei().naitiPokupatelia(searchTerm);
      this.found_customers = data.data;
    },

    clearSearchResult() {
      this.$refs.client.value = '';
      this.found_customers = [];
      this.selected_customer = null;

      this.$emit('onSearchInputClear', true);
    },

    onCustomerSelect(e) {
      const customer_id = +e.target.dataset.customerId;
      this.selected_customer = this.found_customers.find(c => c.id === customer_id);
      this.$refs.client.value = this.selected_customer.full_name;
      this.found_customers = [];
    },

    onCreateCustomer() {

    },

    validateFormData(data) {

      const errors = [];

      if (!data.from_id || data.from_id === '0') {
        errors.push('Выберите ПВЗ из которого нужно привезти заказ');
      }


      if (data.destination_type === DestinationType.DeliveryPoint) {
        if (!data.to_id || data.to_id === '0') {
          errors.push('Выберите офис куда доставить');
        }
      }

      if (data.destination_type === DestinationType.CustomerHome) {

        if (!this.customer_address) {
          errors.push('Необходимо добавить адрес квартиры или дома, куда будет доставлен заказ');
        }
      }

      if (data.destination_type === DestinationType.ToAddress) {
        if (!data.comment.trim()) {
          errors.push('Добавьте, пожалуйста, в комментарии куда нужно доставить заказ 🙂');
        }
      }

      return errors;
    },

    onFormSubmit() {

      if (!confirm('Вы действительно хотите создать заказ?')) return;

      const value = ref_value(this);

      const data = {
        from_id: value('from_id'),
        to_id: value('to_id') || '',
        barcode_image: this.barcode_image_file,
        barcode_images: this.order_barcodes.map(image => image.data),
        barcode_text: value('barcode_text'),
        destination_type: this.selected_destination_type,
        comment: value('comment'),
        pickup_only_paid: value('pickup_only_paid') ? 1 : 0,
        amount_to_pay_for_customer: value('amount_to_pay_for_customer') || 0
      };


      const validation_errors = this.validateFormData(data);

      if (validation_errors.length > 0) {
        alert(validation_errors.join('\n'));
        return;
      }

      if (this.is_admin_or_manager) {
        data['client_id'] = this.selected_customer.id;
      }

      this.form_submitted = true;
      this.$emit('onFormData', data);

    },

    /**
     * @param file {File}
     */
    onBarcodeImageSelected(file) {

      prochitaFailKakUrlDannie(file)
          .then(async (result) => {

            izmenitRazmerIzobrajenia(result, 350, file)
                .then(data => this.barcode_image = data);

            this.barcode_image_file = await pngToJpeg(file);
          })
          .catch((error) => {
            console.error(error);
          });
    },

  },

}
</script>

<style scoped>

.searcher {
  position: relative;
}

.barcode-image {
  width: 64px;
  cursor: pointer;
}

svg {
  top: -10px;
  right: -5px;
  width: 22px;
  height: 22px;
}

.searcher__results-dropdown {
  position: absolute;
  width: 100%;
  left: 0;
  top: 85px;
  z-index: 1000;
  border: 1px solid var(--bs-secondary-bg-subtle);
  border-radius: var(--bs-border-radius);
}
</style>