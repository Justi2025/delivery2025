
<template>
  <div class="ps-md-2">
    <div id="passportImageViewModal" class="modal fade" tabindex="-1">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Паспорт</h5>
            <button aria-label="Закрыть" class="btn-close" data-bs-dismiss="modal" type="button"></button>
          </div>
          <div class="modal-body d-flex justify-content-center">
            <img class="img-fluid"
                 alt="Фотография паспорта"
                 :src="upload_path(user?.passport_image)"
                 v-if="user?.passport_image && !passport_image"
            />
            <img class="img-fluid" style="max-height: 500px" alt="Выбранный файл" :src="passport_image ?? '/images/no-image.png'" v-else/>
          </div>
          <div class="modal-footer">
            <file-chooser caption="Выбрать изображение" class="btn btn-sm btn-secondary" accept="image/*"
                          @on-file-choose="onPassportImageSelected"/>
            <!--            <file-upload caption="Обновить штрих-код" class="btn btn-sm btn-success" :disabled="!barcode_image" />-->
            <input type="button" class="btn btn-sm btn-success" value="Обновить паспорт" :disabled="!passport_image"
                   @click="onPassportImageUpdate"/>
          </div>
        </div>
      </div>
    </div>

    <div class="row">

      <div class="col-lg-3 p-0">

        <div class="card h-100 m-0">
          <div class="card-body text-center">
            <img :src="profileImageOrDefault(user?.avatar)" alt="profile image"
                 v-if="user?.avatar && !profile_image_data"
                 class="img-fluid rounded-3"/>
            <img :src="profile_image_data" alt="profile image" v-else-if="profile_image_data"
                 class="img-fluid rounded-3"/>
          </div>
          <div class="card-footer text-center">
            <div class="row g-3">
              <div class="col">
                <file-chooser caption="Открыть" class="btn-outline-warning"
                              id="profile-image-open"
                              accept="image/*"
                              @on-file-choose="onProfileImageChosen" />
              </div>
              <div class="col">
                <button class="btn btn-sm btn-outline-warning" :disabled="!profile_image" @click="onProfileImageSave">
                  Сохранить
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-9">
        <table class="table table-responsive table-bordered table-hover">
          <tr>
            <td>Идентификатор</td>
            <td class="fw-bold">{{ user?.id || '-' }}</td>
          </tr>

          <tr>
            <td>ФИО</td>
            <td class="fw-bold">{{ user?.full_name || '-' }}</td>
          </tr>

          <tr v-if="is_employee">
            <td>Офис</td>
            <td class="fw-bold">{{ office || '-' }}</td>
          </tr>

          <tr>
            <td>Возраст</td>
            <td class="fw-bold">{{ calculateAge(user?.year_of_birth) }}</td>
          </tr>

          <tr>
            <td>Город</td>
            <td class="fw-bold">{{ user?.city?.name || '-' }}</td>
          </tr>

          <tr>
            <td>Адрес</td>
            <td class="fw-bold editable-field">
              {{ address }}
              <span class="editable-field__action" @click="toggleAddressEdit">
                Редактировать
              </span>
              <div class="row g-3 mt-1" v-if="address_form_visible">
                <div class="col-md-4">
                  <label for="street" class="form-label d-none">Улица</label>
                  <input type="text" class="form-control form-control-sm" id="street" placeholder="Название Вашей улицы"
                         ref="street">
                </div>
                <div class="col-md-4">
                  <label for="house" class="form-label d-none">Номер дома</label>
                  <input type="text" class="form-control form-control-sm" id="house" placeholder="Введите номер дома"
                         ref="house">
                </div>
                <div class="col-md-4">
                  <label for="flat" class="form-label d-none">Квартира</label>
                  <input type="text" class="form-control form-control-sm" id="flat" value="1"
                         placeholder="Номер квартиры (если есть)" ref="flat">
                </div>
                <div class="col">
                  <button class="btn btn-sm btn-outline-success" @click="onAddressUpdate">Сохранить</button>
                </div>
              </div>
            </td>
          </tr>

          <tr>
            <td>Номер телефона</td>
            <!-- <td class="fw-bold">{{ hideValueWithStars(user?.phone || '0000000') }}</td> -->
            <td class="fw-bold">{{ user?.phone }}</td>
          </tr>

          <tr>
            <td>Бонусный баланс</td>
            <td class="fw-bold">{{ user?.bonus_balance || '-' }}</td>
          </tr>


          <!--<tr>
            <td>Роль</td>
            <td class="fw-bold">{{ Role.nameRu(user?.role) || '-' }}</td>
          </tr>

          <tr>
            <td>Статус</td>
            <td class="fw-bold">{{ UserStatus.nameRu(user?.status) || '-' }}</td>
          </tr>-->

          <tr>
            <td>Зарегистрирован</td>
            <td class="fw-bold">{{ dt_format(user?.created_at || '-') }}</td>
          </tr>

          <tr>
            <td>Паспорт</td>
            <td>
              <!--  v-if="user?.passport_image && !passport_image" -->
              <img class="img-fluid img-thumbnail image-preview"
                   alt="Фотография паспорта"
                   data-bs-toggle="modal"
                   data-bs-target="#passportImageViewModal"
                   :src="upload_path(user?.passport_image)"
                   v-if="user?.passport_image"
              />
              <input
                  type="button" class="btn btn-sm btn-outline-warning"
                  value="Добавить паспорт"
                  data-bs-toggle="modal"
                  data-bs-target="#passportImageViewModal"
                  v-else
              />
              <!--<img class="img-fluid img-thumbnail barcode-image" alt="Выбранный штрих-код" :src="passport_image" v-else/>-->
            </td>
          </tr>
        </table>
      </div>
    </div>

  </div>
</template>

<script>
import FileUpload from "../../../komponenti/ZagruzkaFileKnopka.vue";
import vozrast from "../../../utils/vozrast.js";
import {Roli} from "../../../utils/enums/Roli.js";
import {UserStatus} from "../../../utils/enums/UserStatus.js";
import FileChooser from "../../../komponenti/ViborFaila.vue";
import prochitaFailKakUrlDannie from "../../../utils/readAsDataUrl.js";
import {accountService} from "../../../api/akkaunti/ServisAkkauntov.js";
import {ref_value, ref_value_set} from "../../../utils/index.js";
import AdresPolzovatelia from "../../../api/akkaunti/AdresPolzovatelia.js";
import {mapMutations} from "vuex";
import {izmenitRazmerIzobrajenia} from "../../../utils/resizeImage.js";
import {pngToJpeg} from "../../../utils/failovie_raznie.js";
import {servicePolzovatelei, usersService} from "../../../api/index.js";

export default {
  name: "UserProfile",
  components: {FileChooser, FileUpload},

  props: {
    user: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      profile_image: null,
      profile_image_data: null,
      address_form_visible: false,

      passport_image: null,
      passport_image_file: null,
    }
  },

  computed: {
    UserStatus() {
      return UserStatus
    },
    Role() {
      return Roli
    },

    office() {
      const _office = this.user?.office;
      return _office && `${_office.city}, ${_office.street} ${_office.house}, ${_office.flat}`;
    },

    address() {
      return `${this.user?.street || '-'}  ${this.user?.house || '-'} , Кв. ${this.user?.flat || '-'}`
    }
  },

  methods: {
    calculateAge: vozrast,

    ...mapMutations(['UPDATE_USER_ADDRESS']),

    onProfileImageChosen(image) {

      this.profile_image = image;

      prochitaFailKakUrlDannie(image)
          .then(data => {
            this.profile_image_data = data;
          })
          .catch((e) => {
            alert(e)
          });
    },


    async onProfileImageSave() {
      try {
        const {data} = await accountService().saveProfileImage(this.profile_image);

        if (data.result) {
          alert('Изображение профиля обновлено успешно!');
          this.profile_image = null;
        }
      } catch (e) {
        alert(e);
      }
    },

    onPassportImageSelected(file) {

      prochitaFailKakUrlDannie(file)
          .then(async (result) => {

            izmenitRazmerIzobrajenia(result, 550, file)
                .then(data => this.passport_image = data);

            this.passport_image_file = await pngToJpeg(file);
          })
          .catch((error) => {
            console.error(error);
          });
    },

    async onPassportImageUpdate() {
      const data = {
        //order_id: this.$route.params.id,
        passport_image: this.passport_image_file,
      }

      try {
        const res = await usersService().obnovitPasport(data);

        if (res.data.result) {
          // this.order.barcode_image = this.barcode_image;
          alert("Изображение паспорта обновлено успешно");
        }
      } catch (e) {
        alert(e)
      }
    },

    profileImageOrDefault(image) {
      return !image?.trim() ? 'images/no_avatar.png' : `images/avatars/${image}`;
    },

    hideValueWithStars(value) {
      const str = String(value).trim();
      const last4DigitsStr = str.substring(5);
      return last4DigitsStr.padStart(7, '*');
    },

    toggleAddressEdit() {
      this.address_form_visible = !this.address_form_visible;

      this.fillAddressForm();
    },

    fillAddressForm() {
      const value_set = ref_value_set(this);

      // This is needed as at the moment of inserting form to dom
      // ref elements is nullable, so we should wait some time
      setTimeout(() => {
        value_set('street', this.user?.street);
        value_set('flat', this.user?.flat);
        value_set('house', this.user?.house);
      });

    },

    onAddressUpdate() {

      const value = ref_value(this);

      const data = {
        street: value('street'),
        house: value('house'),
        flat: value('flat'),
      };

      accountService()
          .updateAddress(AdresPolzovatelia.from(data))
          .then(res => {
            if (res.data.result) {
              alert('Адрес успешно сохранен');
              this.UPDATE_USER_ADDRESS(data);
              this.toggleAddressEdit()
            }
          })
          .catch(e => {
            alert(e);
          });
    }
  }
}
</script>

<style scoped>
.row {
  /*border-bottom: 1px solid var(--bs-secondary);*/
  padding: 5px 0;
}

table tr td {
  padding: 10px;
}

.image-preview {
  width: 64px;
  cursor: pointer;
}

.editable-field__action {
  margin-top: 10px;
  visibility: hidden;
}

.editable-field:hover .editable-field__action {
  display: inline;
  cursor: pointer;
  visibility: visible;
  color: var(--bs-primary);
  font-weight: normal;
}

.editable-field__action:hover {
  text-decoration: underline;
}

</style>
