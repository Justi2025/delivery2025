
<template>
  <obertka-adminki>

    <template v-if="userCreationFormOpen">
      <zagolovok-stranitc caption="Новый сотрудник">
        <button v-if="is_admin()" class="btn btn-sm btn-warning d-flex" @click="toggleUserCreationForm">
          Закрыть
        </button>
      </zagolovok-stranitc>

      <new-account-form @on-form-close="toggleUserCreationForm" />
    </template>

    <template v-else>
      <zagolovok-stranitc :caption="$route.meta.caption">
        <button v-if="is_admin()" class="btn btn-sm btn-warning d-flex" @click="toggleUserCreationForm">
          <i class="bi bi-plus-lg me-1"></i>Добавить сотрудника
        </button>
      </zagolovok-stranitc>

      <q-loading :status="loading">

        <div class="row">

          <div class="col-md-12">
            <q-table :cols="cols" :rows="users">
              <template #item="{item}">
                <td>{{ item.full_name }}</td>
                <td>
                  <select :value="item.status" class="form-select form-select-sm" name="user-status"
                          @change="(e) => onUserActivationStatusChange(e, item.id)">
                    <option v-for="(status_name, status_val) in userActivationStatuses"
                            :value="status_val">
                      {{ status_name }}
                    </option>
                  </select>
                </td>
                <td>
                  <select :value="item.role" class="form-select form-select-sm" name="user-role"
                          @change="(e) => onUserRoleChange(e, item.id)">
                    <option v-for="(role_name, role_val) in roles" :value="role_val">
                      {{ role_name }}
                    </option>
                  </select>
                </td>
                <td>
                  <select v-model="item.office_id" class="form-select form-select-sm" name="user-office"
                          @change="(e) => onUserOfficeChange(e, item.id)">
                    <option :value="null" disabled selected>Выберите офис</option>
                    <option v-for="dp in delivery_points" :value="dp.id" :key="dp.id">
                      {{ dp.address }}
                    </option>
                  </select>
                </td>
                <td>{{ dt_format(item.created_at) }}</td>
                <td>
                  <router-link :to="url('employee.profile', {id: item.id})">Профиль</router-link>
                </td>
              </template>
            </q-table>
          </div>

        </div>

        <div class="row">
          <pagination :pagination="pagination"/>
        </div>
      </q-loading>
    </template>
  </obertka-adminki>
</template>

<script>
import QLoading from "../../komponenti/QLoading.vue";
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import {usersService} from "../../api/index.js";
import QTable from "../../komponenti/QTable.vue";
import TablePagination from "../../komponenti/TablePagination.vue";
import Pagination from "../../komponenti/Pagination.vue";
import UserCardFragment from "../klienti/UserCardFragment.vue";
import NewAccountForm from "../../komponenti/akkaunti/SozdatNoviAkkauntForma.vue";
import {employeeService} from "../../api/ServisSotrudnikov.js";
import {deliveryPointsService} from "../../api/ServisPvzs.js";

export default {
  name: "UsersPage",
  components: {
    NewAccountForm,
    UserCardFragment, Pagination, TablePagination, QTable, ObertkaAdminki, ZagolovokStranitc, QLoading},
  data() {
    return {
      users: [],
      page: null,
      loading: false,
      cols: {
        name: 'Имя',
        status: 'Статус',
        role: 'Роль',
        office: 'Офис',
        created_at: 'Зарегистрирован',
        actions: 'Действия'
      },
      userActivationStatuses: {
        '1': 'Активирован',
        '0': 'Не активирован'
      },
      roles: {
        '2': 'Менеджер',
        '3': 'Курьер',
        '4': 'Кладовщик',
        '5': 'Клиент',
        '6': 'Оператор',
      },
      pagination: null,
      usernameText: '',
      userCreationFormOpen: false,

      delivery_points: []
    }
  },
  async mounted() {
    await this.getUsers();
    await this.loadDeliveryPoints();
  },

  computed: {
    isClientsPage() {
      return this.$route.name === 'clients';
    }
  },

  methods: {

    async loadDeliveryPoints() {
      const {data} = await deliveryPointsService().list('ab');
      this.delivery_points = data.data.map(d => ({ id: d.id, address: `${d.city}, ${d.street} ${d.house}` }));
    },

    toggleUserCreationForm(e) {
      console.log(e)
        this.userCreationFormOpen = !this.userCreationFormOpen;
    },

    async onUsernameSearch() {
      return this.usernameText.trim() !== ''
          ? await this.searchUsers(this.usernameText)
          : await this.getUsers();
    },

    async searchUsers(username) {
    },

    async getUsers() {
      try {
        this.loading = true;
        const {data} = await employeeService().paginate(this.$route.query);
        this.users = data.data;
        this.pagination = data;
      } catch (e) {
        // alert(e);
        console.error(e);
      } finally {
        this.loading = false;
      }
    },

    async onUserActivationStatusChange(e, user_id) {
      try {
        await usersService().changeActivationStatus(e.target.value, user_id);
      } catch (e) {
        alert(e);
      }
    },

    async onUserRoleChange(e, user_id) {
      try {
        await usersService().changeRole(e.target.value, user_id);
      } catch (e) {
        alert(e);
      }
    },

    async onUserOfficeChange(e, user_id) {
      try {
        await employeeService().changeOffice(e.target.value, user_id);
      } catch (e) {
        alert(e);
      }
    },
  }
}
</script>

<style scoped>

</style>
