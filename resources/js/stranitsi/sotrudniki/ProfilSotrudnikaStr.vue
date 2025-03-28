
<template>
  <obertka-adminki>
    <zagolovok-stranitc caption="Карточка сотрудника">
      <router-link :to="url('employees')" class="btn btn-sm btn-outline-warning">Назад</router-link>
    </zagolovok-stranitc>

    <div class="container-fluid">
      <div class="modal fade" id="passportImageViewModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Паспорт</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
              <img class="img-fluid"
                   alt="Фото паспорта"
                   :src="upload_path(employee?.passport_image)"
                   v-if="employee?.passport_image"
              />
            </div>
          </div>
        </div>
      </div>

      <div class="row">

        <div class="col-md-3 p-0">

          <div class="card h-100 m-0">
            <div class="card-body text-center">
              <img :src="profileImageOrDefault(employee?.avatar)" alt="profile image"
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
              <td class="fw-bold">{{ employee?.id || '-' }}</td>
            </tr>

            <tr>
              <td>ФИО</td>
              <td class="fw-bold">{{ employee?.full_name || '-' }}</td>
            </tr>

            <tr>
              <td>Возраст</td>
              <td class="fw-bold">{{ calculateAge(employee?.year_of_birth) }}</td>
            </tr>

            <tr>
              <td>Город</td>
              <td class="fw-bold">{{ employee?.city?.name || '-' }}</td>
            </tr>

            <tr>
              <td>Номер телефона</td>
              <!-- <td class="fw-bold">{{ hideValueWithStars(employee?.phone || '0000000') }}</td> -->
              <td class="fw-bold">{{ employee?.phone }}</td>
            </tr>

            <tr>
              <td>Бонусный баланс</td>
              <td class="fw-bold">{{ employee?.bonus_balance || '-' }}</td>
            </tr>

            <tr>
              <td>Роль</td>
              <td class="fw-bold">{{ Role.nameRu(employee?.role) || '-' }}</td>
            </tr>

            <tr>
              <td>Статус</td>
              <td class="fw-bold">{{ UserStatus.nameRu(employee?.status) || '-' }}</td>
            </tr>

            <tr>
              <td>Зарегистрирован</td>
              <td class="fw-bold">{{ dt_format(employee?.created_at || '-') }}</td>
            </tr>

            <tr>
              <td>Паспорт</td>
              <td>
                <img class="img-fluid img-thumbnail image-preview"
                     alt="Паспорт клиента"
                     data-bs-toggle="modal" data-bs-target="#passportImageViewModal"
                     :src="upload_path(employee?.passport_image)"
                     v-if="employee?.passport_image"
                />
                <button class="btn btn-sm btn-outline-warning" v-else>Добавить фото паспорта</button>
              </td>
            </tr>
          </table>
        </div>
      </div>

    </div>

  </obertka-adminki>
</template>

<script>
import FileUpload from "../../komponenti/ZagruzkaFileKnopka.vue";
import FileChooser from "../../komponenti/ViborFaila.vue";
import {Roli} from "../../utils/enums/Roli.js";
import {UserStatus} from "../../utils/enums/UserStatus.js";
import vozrast from "../../utils/vozrast.js";
import {employeeService} from "../../api/ServisSotrudnikov.js";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";

export default {
  name: "EmployeeProfilePage",
  computed: {
    UserStatus() {
      return UserStatus
    },
    Role() {
      return Roli
    }
  },
  components: {ZagolovokStranitc, ObertkaAdminki, FileChooser, FileUpload},

  data: () => ({
    employee: null
  }),

  async mounted() {
    const id = +this.$route.params.id;
    await this.getUserProfileData(id);
  },

  methods: {
    calculateAge: vozrast,

    async getUserProfileData(user_id) {
      const res = await employeeService().getById(user_id);
      this.employee = res.data;
    },

    profileImageOrDefault(image) {
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
