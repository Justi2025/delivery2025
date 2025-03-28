
<template>

  <main-wrapper>
    <main class="container d-flex flex-column align-items-md-center justify-content-md-center pt-2">

      <div v-if="accountCreated" class="shadow-lg rounded py-4 px-4">
        <h4>Ваш аккаунт создан!</h4>
        <p>После активации, перейдите на
          <router-link :to="loginPage">страницу входа</router-link>
          и введите Ваши данные
        </p>
      </div>

      <div v-else class="shadow-lg rounded py-4 px-4 form-signin">
        <h2 :class="{'mb-4': !isStep2}">
          Регистрация.
          <span v-if="isStep1">Шаг 1</span>
          <span v-if="isStep2">Шаг 2</span>
        </h2>

        <div v-show="errorExist" class="alert alert-warning">{{ errors?.message }}</div>

        <form @submit.prevent="handleSubmit" autocomplete="off">

          <template v-if="isStep1">
            <div class="row">
              <div class="col-md-12">
                <div class="alert alert-success mb-3 d-flex">
                  <span class="align-self-start" style="padding-top: 3px">
                    <bi-info-circle/>
                  </span>
                  <span class="ms-2 align-self-start" v-if="codeSent">
                    Сообщение с кодом можно будет получить в Telegram
                  </span>
                  <span class="ms-2 align-self-start" v-else>
                    Вы будете автоматически перенаправлены в Telegram-бот для подтверждения номера телефона
                  </span>
                </div>
              </div>
              <div class="col-md-12 mb-3">
                <label class="form-label required" for="phoneForCodeTextbox">Номер телефона</label>
                <input id="phoneForCodeTextbox"
                       v-model="phone"
                       class="form-control"
                       minlength="7"
                       maxlength="20"
                       pattern="^\+7\s\d{3}\s\d{3}-\d{2}-\d{2}$"
                       required
                       placeholder="+7 999 123-45-67"
                       type="tel"
                       v-maska data-maska="+7 ### ###-##-##"
                />
              </div>

              <div class="col-md-12 mb-3">
                <label class="form-label required" for="authCode">Код из сообщения</label>
                <input id="authCode" v-model="authCode" class="form-control" min="6" required type="text"
                :disabled="!codeSent"/>
                <span class="text-secondary mt-2 d-inline-block" style="font-size: .8rem">Можно получить в Telegram боте</span>
              </div>

            </div>

            <div class="col-md-12 mb-3" v-if="verification_url">
              <a :href="verification_url" class="btn btn-sm btn-warning" target="_blank">Получить код в Телеграм</a>
            </div>

            <button :disabled="isLoading" class="mt-4 btn btn-dark w-100" type="submit">
              {{ !codeSent ? 'Получить код' : 'Подтвердить номер' }}
            </button>
          </template>

          <template v-else-if="isStep2">

            <div class="my-3">
              <span class="required text-secondary">Обязательные поля</span>
            </div>

            <div class="row">

              <div class="col-md-12 mb-3">
                <label class="form-label required" for="phoneTextbox">Номер телефона</label>
                <input id="phoneTextbox" :value="phone" class="form-control" disabled type="tel"/>
              </div>
            </div>


            <div class="row">
              <div class="col-md-12 mb-3">
                <label class="form-label required" for="passwordTextbox">Пароль</label>
                <input id="passwordTextbox" v-model="password" class="form-control" min="6" required
                       autocomplete="new-password"
                       :disabled="password_field_disabled"
                       type="password"/>
              </div>

              <div class="col-md-12 mb-3">
                <label class="form-label required" for="passwordTextboxRepeat">Пароль еще раз</label>
                <input id="passwordTextboxRepeat" v-model="password_confirmation" class="form-control"
                       min="6"
                       :disabled="password_field_disabled"
                       autocomplete="new-password"
                       required type="password"/>
              </div>
            </div>

            <div class="row">

              <div class="col-md-6 mb-3">
                <label class="form-label required" for="lastnameTextbox">Фамилия</label>
                <input id="lastnameTextbox" ref="lastname" class="form-control" min="5" required type="text"
                       v-model="lastname"/>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label required" for="firstnameTextbox">Имя</label>
                <input id="firstnameTextbox" ref="firstname" class="form-control" min="5" required type="text"
                       v-model="firstname"/>
              </div>

            </div>

            <div class="row">
              <div class="col mb-3">
                <label class="form-label" for="middlename">Отчество</label>
                <input id="middlename" ref="middlename" class="form-control" min="5" type="text"
                       v-model="middlename"/>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label required" for="citySelect">Ваш город</label>
                <select id="citySelect" class="form-select" v-model="city_id" required>
                  <option value="" disabled>Выберите город</option>
                  <option :value="city.id" v-for="city in cities" :key="city.id">{{ city.name }}</option>
                </select>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label required" for="year_of_birth">Год рождения</label>
                <select id="year_of_birth" class="form-select" v-model="year_of_birth">
                  <option value="" disabled>Выберите год</option>
                  <option :value="year" v-for="year in range(1950, 2024)" :key="year">{{ year }}</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label" for="street">Улица</label>
                <input id="street" class="form-control" min="5" type="text" v-model="street"/>
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label" for="house">Дом</label>
                <input id="house" class="form-control" min="5" type="text" v-model="house"/>
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label" for="flat">Квартира</label>
                <input id="flat" class="form-control" min="5" type="text" v-model="flat"/>
              </div>
            </div>

            <button :disabled="isLoading" class="mt-4 btn btn-dark w-100" type="submit">
              Создать аккаунт
            </button>
          </template>

        </form>

        <div class="text-secondary text-center mt-2 text-decoration-underline cursor-pointer" style="font-size: .85rem"
             @click="verifyRetry"
             v-if="codeSent"
        >
          Начать сначала
        </div>

        <div class="my-3 text-center">
          Если у Вас уже есть аккаунт, то можно
          <router-link :to="loginPage">Войти</router-link>
        </div>

      </div>
    </main>
  </main-wrapper>

</template>

<script>
import MainWrapper from "../komponenti/MainWrapper.vue";
import FileUpload from "../komponenti/ZagruzkaFileKnopka.vue";
import {authService, citiesService} from "../api/index.js";
import BiInfoCircle from "../komponenti/ikonki/BiInfoCircle.vue";
import {mapGetters, mapMutations} from "vuex";
import FormStep from "../utils/formStep.js";


export default {
  name: "CreateAccountPage",
  components: {BiInfoCircle, FileUpload, MainWrapper},
  data() {
    return {
      profile_image: null,
      accountCreated: false,
      isLoading: false,
      errors: null,
      cities: [],

      verification_url: ''
    }
  },
  async mounted() {
    document.title = 'Регистрация';

    this.cities = await citiesService().poluchitGoroda();
  },
  computed: {

    ...mapGetters('authStore', ['formStep', 'codeSent', 'regForm']),

    password_field_disabled() {
        return this.authCode.toString().length !== 6;
    },

    phone: {
      get() {
        return this.regForm.phone;
      },
      set(value) {
        this.SET_REGISTRATION_FORM_FIELD({phone: value});
      }
    },

    password: {
      get() {
        return this.regForm.password;
      },
      set(value) {
        this.SET_REGISTRATION_FORM_FIELD({password: value});
      }
    },

    password_confirmation: {
      get() {
        return this.regForm.password_confirmation;
      },
      set(value) {
        this.SET_REGISTRATION_FORM_FIELD({password_confirmation: value});
      }
    },

    authCode: {
      get() {
        return this.regForm.code;
      },
      set(value) {
        this.SET_REGISTRATION_FORM_FIELD({code: value});
      }
    },

    firstname: {
      get() {
        return this.regForm.firstname;
      },
      set(value) {
        this.SET_REGISTRATION_FORM_FIELD({firstname: value});
      }
    },

    lastname: {
      get() {
        return this.regForm.lastname;
      },
      set(value) {
        this.SET_REGISTRATION_FORM_FIELD({lastname: value});
      }
    },

    middlename: {
      get() {
        return this.regForm.middlename;
      },
      set(value) {
        this.SET_REGISTRATION_FORM_FIELD({middlename: value});
      }
    },

    city_id: {
      get() {
        return this.regForm.city_id;
      },
      set(value) {
        this.SET_REGISTRATION_FORM_FIELD({city_id: value});
      }
    },

    year_of_birth: {
      get() {
        return this.regForm.year_of_birth;
      },
      set(value) {
        this.SET_REGISTRATION_FORM_FIELD({year_of_birth: value});
      }
    },

    street: {
      get() {
        return this.regForm.street;
      },
      set(value) {
        this.SET_REGISTRATION_FORM_FIELD({street: value});
      }
    },

    house: {
      get() {
        return this.regForm.house;
      },
      set(value) {
        this.SET_REGISTRATION_FORM_FIELD({house: value});
      }
    },

    flat: {
      get() {
        return this.regForm.flat;
      },
      set(value) {
        this.SET_REGISTRATION_FORM_FIELD({flat: value});
      }
    },

    loginPage() {
      return this.$router.resolve({name: 'login'}).fullPath
    },
    errorExist() {
      return this.errors !== null;
    },
    isStep1() {
      return (this.formStep === FormStep.PhoneConfirm) || (this.formStep === FormStep.CodeCheck);
    },
    isStep2() {
      return this.formStep === FormStep.Registration;
    }
  },
  methods: {

    ...mapMutations('authStore', ['SET_REGISTRATION_FORM_FIELD', 'CLEAR_REGISTRATION_FORM', 'SET_AUTH_SUCCESS']),

    verifyRetry() {
      this.verification_url = null; // Todo: move to store
      this.CLEAR_REGISTRATION_FORM();
    },

    async register() {
      const form = this.regForm;

      this.SET_REGISTRATION_FORM_FIELD(form);

      try {
        this.isLoading = true;
        const {data} = await authService().register({
          ...form,
          phone: form.phone.replace(/\D/g,''),
          full_name: `${form.lastname?.trim()} ${form.firstname?.trim()} ${form.middlename?.trim()}`
        });

        if (data) {
          // this.accountCreated = true;
          this.SET_AUTH_SUCCESS(data);
          window.location.href = '/';
        }
      } catch (error) {
        this.errors = error.response.data;
        console.log(error.response.data);
      } finally {
        this.isLoading = false;
      }
    },


    async sendCode() {

      this.errors = null;
      // const value = ref_value(this);

      try {
        this.isLoading = true;
        const res = await authService().sendCode(
            this.phone.replace(/\D/g,''),
            this.password,
            this.password_confirmation
        );

        if (200 === res.status) {

          // Todo: for development
          // alert(JSON.stringify(res.data));

          // this.verification_url = res.data.url;

          this.SET_REGISTRATION_FORM_FIELD({
            codeSent: true,
            formStep: FormStep.CodeCheck,
            phone: this.phone,
            password: this.password,
            password_confirmation: this.password_confirmation
          });

          setTimeout(() => {
            window.location.href = res.data.url;
          }, 100);

        }
      } catch (error) {
        //alert(error);
        this.errors = error.response.data;
        console.log(error.response.data);
      } finally {
        this.isLoading = false;
      }
    },

    async checkCode() {

      this.errors = null;

      try {
        this.isLoading = true;
        const res = await authService().checkCode(this.authCode, this.phone.replace(/\D/g,''));

        if (200 === res.status) {
          this.SET_REGISTRATION_FORM_FIELD({
            formStep: FormStep.Registration
          });
        }
      } catch (error) {
        this.errors = error.response.data;
        console.log(error.response.data);
      } finally {
        this.isLoading = false;
      }
    },

    async handleSubmit() {

      switch (this.formStep) {
        case FormStep.PhoneConfirm:
          await this.sendCode();

          break;

        case FormStep.CodeCheck:
          await this.checkCode();
          break;

        case FormStep.Registration:
          await this.register();
          break;
      }
    }
  }
}
</script>

<style scoped>

label {
  color: var(--bs-secondary);
}

.form-signin {
  --width-v1: 530px;
  --width-v2: 600px;
  max-width: var(--width-v2);
}

#phoneForCodeTextbox::placeholder {
  color: var(--bs-secondary);
}

</style>
