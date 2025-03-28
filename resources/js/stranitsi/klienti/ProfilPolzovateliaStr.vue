/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <div class="ps-md-2">
    <!-- &copy; kk2024 -->
    <div class="modal fade" id="passportImageViewModal" tabindex="-1">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Паспорт</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
          </div>
          <div class="modal-body d-flex justify-content-center">
            <img class="img-fluid"
                 alt="Паспорт"
                 :src="upload_path(customer?.passport_image)"
                 v-if="customer?.passport_image"
            />
            <img class="img-fluid" style="max-height: 500px" alt="Выбранный изображение" :src="izobrajenie_pasporta" v-else/>
          </div>
          <div class="modal-footer">
            <file-chooser caption="Выбрать изображение" class="btn btn-sm btn-secondary" accept="image/*"
                          @on-file-choose="onPassportImageSelected"/>
            <input type="button" class="btn btn-sm btn-success" value="Обновить штрих-код" :disabled="!izobrajenie_pasporta"
                   @click="obnovitIzobrajeniePasporta"/>
          </div>
        </div>
      </div>
    </div>

    <div class="row">

      <div class="col-md-3 p-0">

        <div class="card h-100 m-0">
          <div class="card-body text-center">
            <img :src="izobrajebnieProfiliaIliPoUmolchaniu(customer?.avatar)" alt="profile image"
                 class="img-fluid rounded-3"/>
          </div>
          <div class="card-footer text-center">
            <div>
              <file-upload accept="image/*" caption="Загрузить" class="btn-warning"
                           dest="avatars" @on-upload="onProfileImageUploaded"/>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-9">
        <table class="table table-responsive table-bordered">
          <tr>
            <td>Идентификатор</td>
            <td class="fw-bold">{{ customer?.id || '-' }}</td>
          </tr>

          <tr>
            <td>ФИО</td>
            <td class="fw-bold">{{ customer?.full_name || '-' }}</td>
          </tr>

          <tr>
            <td>Возраст</td>
            <td class="fw-bold">{{ calculateAge(customer?.year_of_birth) }}</td>
          </tr>

          <tr>
            <td>Город</td>
            <td class="fw-bold">{{ customer?.city.name || '-' }}</td>
          </tr>

          <tr>
            <td>Номер телефона</td>
            <td class="fw-bold">{{ customer?.phone }}</td>
          </tr>

          <tr>
            <td>Бонусный баланс</td>
            <td class="fw-bold">{{ customer?.bonus_balance || '-' }}</td>
          </tr>

          <tr>
            <td>Роль</td>
            <td class="fw-bold">{{ Role.nameRu(customer?.role) || '-' }}</td>
          </tr>

          <tr>
            <td>Статус</td>
            <td class="fw-bold">{{ UserStatus.nameRu(customer?.status) || '-' }}</td>
          </tr>

          <tr>
            <td>Зарегистрирован</td>
            <td class="fw-bold">{{ dt_format(customer?.created_at || '-') }}</td>
          </tr>

          <tr>
            <td>Паспорт</td>
            <td>
              <img class="img-fluid img-thumbnail image-preview"
                   alt="Штрихкод заказа"
                   data-bs-toggle="modal" data-bs-target="#passportImageViewModal"
                   :src="upload_path(customer?.passport_image)"
                   v-if="customer?.passport_image && !izobrajenie_pasporta"
              />
              <img class="img-fluid img-thumbnail barcode-image" alt="Фото паспорта" :src="izobrajenie_pasporta" v-else/>
            </td>
          </tr>
        </table>
      </div>
    </div>

  </div>
</template>

<script>
import FileUpload from "../../komponenti/ZagruzkaFileKnopka.vue";
import {servicePolzovatelei} from "../../api/index.js";
import FileChooser from "../../komponenti/ViborFaila.vue";
import {Roli} from "../../utils/enums/Roli.js";
import {UserStatus} from "../../utils/enums/UserStatus.js";
import vozrast from "../../utils/vozrast.js";
import prochitaFailKakUrlDannie from "../../utils/readAsDataUrl.js";
import {izmenitRazmerIzobrajenia} from "../../utils/resizeImage.js";
import {pngToJpeg} from "../../utils/failovie_raznie.js";

export default {
  name: "CustomerProfilePage",
  computed: {
    UserStatus() {
      return UserStatus
    },
    Role() {
      return Roli
    }
  },
  components: {FileChooser, FileUpload},

  data: () => ({
    customer: null,
    izobrajenie_pasporta: null,
    fail_izobrajenia_pasporta: null,
  }),

  async mounted() {
    const id = +this.$route.params.id;
    await this.getUserProfileData(id);
  },

  methods: {
    calculateAge: vozrast,

    onPassportImageSelected(file) {
      prochitaFailKakUrlDannie(file)
          .then(async (result) => {

            izmenitRazmerIzobrajenia(result, 350, file)
                .then(data => this.izobrajenie_pasporta = data);

            this.fail_izobrajenia_pasporta = await pngToJpeg(file);
          })
          .catch((error) => {
            console.error(error);
          });
    },

    async obnovitIzobrajeniePasporta() {
      try {

        const res = await servicePolzovatelei().obnovitPasport({
          file: this.fail_izobrajenia_pasporta,
        });

        if (res.data.result) {
          alert("Изображение паспорта изменено успешно");
        }
      } catch (e) {
        alert("Ошибка изменения паспорта")
      }
    },

    async getUserProfileData(user_id) {
      const res = await servicePolzovatelei().getById(user_id);
      this.customer = res.data;
    },

    izobrajebnieProfiliaIliPoUmolchaniu(image) {
      return !image?.trim() ? '/images/no_avatar.png' : `/images/avatars/${image}`;
    },


    onProfileImageUploaded(profileImage) {

    }
  }
}
</script>

<style scoped>
.row {
  padding: 5px 0;
}

table tr td {
  padding: 10px;
}

.image-preview {
  width: 64px;
  cursor: pointer;
}
</style>
