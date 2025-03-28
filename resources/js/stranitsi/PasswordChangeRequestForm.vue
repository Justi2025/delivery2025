/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <main-wrapper>
    <main class="container h-100 d-flex flex-column align-items-md-center justify-content-md-center pt-2">

      <div v-if="isOk" class="shadow-lg rounded py-4 px-4">
        <h4>Ваш пароль успешно изменен!</h4>
        <p>Теперь Вы можете <router-link :to="href('login')">войти</router-link> в личный кабинет снова</p>
      </div>

      <div v-else class="shadow-lg rounded py-4 px-4 form-signin">
        <h2 class="mb-4">Восстановление доступа</h2>

        <div v-if="errorExist" class="alert alert-warning">{{ errors?.message }}</div>

        <form @submit.prevent="handleSubmit">

          <div class="row">

            <div class="col-md-12">
              <div class="alert alert-success mb-3 d-flex">
                  <span class="align-self-start" style="padding-top: 3px">
                    <bi-info-circle/>
                  </span>
                <span class="ms-2 align-self-start" v-if="codeSent">
                    Сообщение с кодом отправлено в Telegram
                  </span>
                <span class="ms-2 align-self-start" v-else>
                    Сообщение с кодом придет на введенный Вами номер в Telegram
                  </span>
              </div>
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label required" for="passwordChangePhoneTextbox">Номер телефона</label>
              <input id="passwordChangePhoneTextbox" v-model="phone" class="form-control" minlength="7"
                     pattern="^\+7\s\d{3}\s\d{3}-\d{2}-\d{2}$"
                     required
                     type="tel"
                     v-maska data-maska="+7 ### ###-##-##"
              />
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label required" for="authCode">Код из сообщения</label>
              <input id="authCode" v-model="code" class="form-control" min="6" required type="text"
                     :disabled="!codeSent"/>
            </div>

          </div>

          <div class="row">
            <div class="mb-3">
              <label class="form-label required" for="passwordChangeTextbox">Новый пароль</label>
              <input id="passwordChangeTextbox" v-model="password" class="form-control" min="6"
                     required
                     :disabled="!codeSent"
                     type="password"/>
            </div>

            <div class="mb-3">
              <label class="form-label required" for="passwordChangeTextboxRepeat">Пароль еще раз</label>
              <input id="passwordChangeTextboxRepeat" v-model="password_confirmation" class="form-control"
                     min="6"
                     :disabled="!codeSent"
                     required type="password"/>
            </div>
          </div>


          <button :disabled="is_loading" class="mt-4 btn btn-dark w-100" type="submit">
            {{ codeSent ? 'Сменить пароль' : 'Получить код' }}
          </button>
        </form>

        <div class="my-3 text-center">
          <router-link :to="href('login')">На страницу входа</router-link>
        </div>

      </div>
    </main>
  </main-wrapper>
</template>

<script>
import MainWrapper from "../komponenti/MainWrapper.vue";
import {authService} from "../api/index.js";
import {mapGetters, mapMutations} from "vuex";
import BiInfoCircle from "../komponenti/ikonki/BiInfoCircle.vue";

export default {
  name: "PasswordChangeRequestForm",
  components: {BiInfoCircle, MainWrapper},

  data: () => ({
    is_loading: false,
    // errorExist: false,
    isOk: false,
    errors: null,
    error_message: ''
  }),

  computed: {

    ...mapGetters('authStore', ['recoveryForm']),

    codeSent() {
        return this.recoveryForm.codeSent;
    },

    phone: {
      get() {
          return this.recoveryForm.phone;
      },
      set(value) {
          this.SET_RECOVERY_FORM_FIELD({ phone: value });
      }
    },

    code: {
      get() {
        return this.recoveryForm.code;
      },
      set(value) {
        this.SET_RECOVERY_FORM_FIELD({ code: value });
      }
    },

    password: {
      get() {
        return this.recoveryForm.password;
      },
      set(value) {
        this.SET_RECOVERY_FORM_FIELD({ password: value });
      }
    },

    password_confirmation: {
      get() {
        return this.recoveryForm.password_confirmation;
      },
      set(value) {
        this.SET_RECOVERY_FORM_FIELD({ password_confirmation: value });
      }
    },

    errorExist() {
      return this.errors;
    }
  },

  beforeRouteLeave(to, from) {
    this.CLEAR_RECOVERY_FORM();
  },

  methods: {

    ...mapMutations('authStore', ['SET_RECOVERY_FORM_FIELD', 'CLEAR_RECOVERY_FORM']),


    async sendCode() {

      this.errors = null;
      try {
        this.isLoading = true;
        const res = await authService().sendRecoveryCode(this.phone.replace(/\D/g,''));

        if (200 === res.status) {

          this.SET_RECOVERY_FORM_FIELD({
            codeSent: true,
            phone: this.phone.replace(/\D/g,''),
          });
        }
      } catch (error) {
        this.errors = error.response.data;
      } finally {
        this.isLoading = false;
      }
    },

    async changePassword() {
      this.errors = null;

      try {
        this.isLoading = true;
        const res = await authService().changePassword(
            this.phone.replace(/\D/g,''), this.code,
            this.password, this.password_confirmation
        );

        if (200 === res.status) {

          this.CLEAR_RECOVERY_FORM();
          this.isOk = true;
        }
      } catch (error) {
        this.errors = error.response?.data;
      } finally {
        this.isLoading = false;
      }
    },

    async handleSubmit() {

      if (!this.codeSent) {
        await this.sendCode();
      } else {
        await this.changePassword();
      }
    }
  }
}
</script>

<style scoped>
.form-signin {
  --width-v1: 530px;
  --width-v2: 600px;
  max-width: var(--width-v2);
}
</style>
