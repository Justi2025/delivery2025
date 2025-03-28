
<template>
  <main-wrapper>
    <main class="container h-100 d-flex flex-column align-items-md-center justify-content-md-center pt-2">
      <div class="shadow-lg rounded py-4 px-4 form-signin">
        <h4 class="mb-4">Вход на сервис</h4>

        <div v-show="error != null" class="alert alert-warning">
          {{ formatErrorMessage(error?.message) || 'Неизвестная ошибка' }}
        </div>

        <form @submit.prevent="handleSubmit">

          <div class="row">

            <div class="mb-3">
              <label class="form-label" for="phoneTextbox">Логин</label>
              <input id="phoneTextbox" ref="phone" class="form-control" minlength="7"
                     required pattern="^\+7\s\d{3}\s\d{3}-\d{2}-\d{2}$"
                     type="tel" v-maska data-maska="+7 ### ###-##-##"/>
            </div>

            <div class="mb-3">
              <label class="form-label" for="passwordTextbox">Пароль</label>
              <input id="passwordTextbox" ref="password" class="form-control" min="6" required
                     type="password"/>
            </div>

            <router-link :to="route_url('auth.account_recovery')" class="text-secondary text-decoration-underline">
              Восстановить пароль
            </router-link>
          </div>

          <button :disabled="isLoading" class="mt-4 btn btn-dark w-100" type="submit">
            Войти
          </button>
        </form>

        <div class="my-3 text-center">
          Если у Вас еще нет аккаунта, то можете здесь
          <router-link :to="route_url('signup')">создать аккаунт</router-link>
        </div>

      </div>
    </main>
  </main-wrapper>
</template>

<script>
import MainWrapper from "../komponenti/MainWrapper.vue";
import {ref_value} from "../utils/index.js";
import {mapActions, mapMutations} from "vuex";
import {authService} from "../api/index.js";

export default {
  name: "LoginPage",
  components: {MainWrapper},
  data() {
    return {
      isLoading: false,
      error: null
    }
  },
  mounted() {
    document.title = 'Авторизация';
  },
  computed: {

  },
  methods: {

    ...mapActions('authStore', ['login']),
    ...mapMutations('authStore', ['SET_AUTH_SUCCESS']),


    formatErrorMessage(error) {
      return error;
    },

    getFormFields() {

      const value = ref_value(this);

      return {
        phone: value('phone').replace(/\D/g, ''),
        password: value('password')
      }
    },

    async handleSubmit() {
      this.isLoading = true;
      this.error = null;

      try {
        const res = await authService().login(this.getFormFields());

        if(res.data.access_token) {
          this.SET_AUTH_SUCCESS(res.data);

          let redirect_url = '';

          if(this.is_admin || this.is_manager) {
            redirect_url = this.url('dostavka');
          }

          if(this.is_courier) {
            redirect_url = this.url('dostavka.assigned_to_courier');
          }

          if(this.is_storekeeper) {
            redirect_url = this.url('dostavka.received_by_courier');
          }

          if(this.is_operator) {
            redirect_url = this.url('waiting-for-customers');
          }

          window.location.href = redirect_url;
        }

      } catch (e) {
        this.error = e?.response?.data;
      } finally {
        this.isLoading = false;
      }
    }
  }
}
</script>

<style scoped>
.form-signin {
  --width-v1: 530px;
  --width-v2: 600px;
  max-width: var(--width-v1);
}

</style>
