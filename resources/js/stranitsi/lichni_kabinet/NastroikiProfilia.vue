
<script>
import Switcher from "../../komponenti/Perekluchatel.vue";
import {ref_value} from "../../utils/index.js";
import {accountService} from "../../api/akkaunti/ServisAkkauntov.js";
import {servisNastroekAdmina} from "../../api/nastroiki/NastroikiAdminaServis.js";
import {servisNastroekPolzovatelia} from "../../api/nastroiki/NastroikiPolzovaliaServis.js";
import {mapGetters} from "vuex";

export default {
  name: "ProfileSettings",
  components: {Switcher},

  data: () => ({
    tg_notifications: false,

    admin_settings: {
      standard_bonus: 0,
      vip_bonus: 0,
      reports_visibility: false
    },

    user_settings: {
      telegram_notifications: false
    },

    tg_url: '',
  }),

  async mounted() {

    if (this.is_admin()) {
      await this.loadAdminSettings();
    }

    await this.loadUserSettings();
  },


  computed: {
    ...mapGetters('authStore', ['user']),
  },

  methods: {
    onPasswordChange() {
      const value = ref_value(this);

      const data = {
        old_password: value('old_password'),
        new_password: value('new_password'),
        new_password_confirmation: value('new_password_confirmation'),
      };

      accountService()
          .changePassword(data)
          .then(res => {
            if (res?.data?.result) {
              alert('Пароль успешно изменен');
              this.clearPasswordChangeForm();
            }
          })
          .catch(e => {
            alert(e?.response?.data?.message);
          });
    },

    clearPasswordChangeForm() {
      this.$refs.old_password.value = '';
      this.$refs.new_password.value = '';
      this.$refs.new_password_confirmation.value = '';
    },

    async onBonusChange(e) {
      const {id, value} = e.target;

      switch (id) {
        case 'bonus_standard':
          await servisNastroekAdmina().ustanovitStandartniBonus(value);
          break;

        case 'bonus_vip':
          await servisNastroekAdmina().ustanovitVipBonus(value);
          break;
      }
    },

    async loadAdminSettings() {
      const {data} = await servisNastroekAdmina().vseNastroki();
      this.admin_settings = {
        ...data.result,
        reports_visibility: Boolean(+data.result.reports_visibility),
        telegram_notifications: Boolean(+data.result.telegram_notifications)
      };
    },

    async loadUserSettings() {
      const {data} = await servisNastroekPolzovatelia().vseNastroiki();
      this.user_settings = {
        ...data.result,
        telegram_notifications: Boolean(+data.result.telegram_notifications)
      };
    },

    async onAccessChange(e) {
      await servisNastroekAdmina().menedgerViditOtcheti(Number(e.target.checked));
    },

    async onNotificationsToggle(e) {
      const checked = Number(e.target.checked);
      const {data} = await servisNastroekPolzovatelia().toggleTelegramNotifications(checked);

      if (data.url) {
        setTimeout(() => {
          window.location.href = data.url;
        }, 100);
      }
    }
  }
}
</script>

<template>
  <div>
    <div class="row g-3">
      <div class="col-md-12">
        <h5 class="h5 border-bottom pb-2">Смена пароля</h5>
      </div>
      <div class="col-sm-12 col-md-12">
        <label for="old_password" class="form-label required">Старый пароль</label>
        <input type="text" class="form-control" id="old_password" ref="old_password">
      </div>
      <div class="col-sm-12 col-md-6">
        <label for="new_password" class="form-label required">Новый пароль</label>
        <input type="text" class="form-control" id="new_password" ref="new_password">
      </div>
      <div class="col-sm-12 col-md-6">
        <label for="new_password_confirmation" class="form-label required">Новый пароль (повторно)</label>
        <input type="text" class="form-control" id="new_password_confirmation" ref="new_password_confirmation">
      </div>
      <div class="col-md-12">
        <input type="button" class="btn btn-sm btn-outline-warning" value="Изменить пароль" @click="onPasswordChange">
      </div>
    </div>

    <div class="row g-3 mt-3">
      <div class="col-md-12">
        <h5 class="h5 border-bottom pb-2">Уведомления</h5>
      </div>
      <div class="col-md-12">
        <switcher id="tg_notification" :checked="user_settings.telegram_notifications"
                  v-model="user_settings.telegram_notifications" @change="onNotificationsToggle">
          Уведомления в Telegram
        </switcher>
      </div>
      <div class="col-md-12" v-if="tg_url">
        <a :href="tg_url" target="_blank">{{ tg_url }}</a>
      </div>
    </div>

    <div class="row g-3 mt-3" v-if="is_admin()">
      <div class="col-md-12">
        <h5 class="h5  border-bottom pb-2">Бонусы (% от доставки)</h5>
      </div>
      <div class="col-sm-12 col-md-6">
        <label for="bonus_standard" class="form-label">Стандарт</label>
        <input type="number" min="0" class="form-control" id="bonus_standard" v-model="admin_settings.standard_bonus"
               @change="onBonusChange"/>
      </div>
      <div class="col-sm-12 col-md-6">
        <label for="bonus_vip" class="form-label">ВИП</label>
        <input type="number" min="0" class="form-control" id="bonus_vip" v-model="admin_settings.vip_bonus"
               @change="onBonusChange"/>
      </div>
    </div>

    <div class="row g-3 mt-3" v-if="is_admin()">
      <div class="col-md-12">
        <h5 class="h5 border-bottom pb-2">Доступы</h5>
      </div>
      <div class="col-md-12">
        <switcher id="reports_visible" :checked="admin_settings.reports_visibility"
                  v-model="admin_settings.reports_visibility"
                  @change="onAccessChange">
          Отчеты доступны менеджеру
        </switcher>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>