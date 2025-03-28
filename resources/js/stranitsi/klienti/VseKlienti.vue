
<template>
  <obertka-adminki>

    <template v-if="userCreationFormOpen">
      <div>
        <zagolovok-stranitc caption="Новый клиент">
          <button class="btn btn-sm btn-outline-warning d-flex" @click="toggleUserCreationForm">
            Закрыть
          </button>
        </zagolovok-stranitc>

        <new-account-form @on-form-close="toggleUserCreationForm" @on-data="onCreateCustomer" :error="error"
                          :updating="loading"/>
      </div>

    </template>

    <template v-else>
      <zagolovok-stranitc :caption="$route.meta.caption">
        <button v-if="is_admin()" class="btn btn-sm btn-warning d-flex" @click="toggleUserCreationForm">
          <i class="bi bi-plus-lg me-1"></i>Добавить клиента
        </button>
      </zagolovok-stranitc>


      <div class="row">

        <div class="col-md-12">
          <input v-model="usernameText" class="form-control form-select-sm mb-3"
                 placeholder="Введите ФИО..." type="text" @keyup="onUsernameSearch"/>
        </div>

        <div class="col-md-12">
          <q-loading :status="loading">
            <q-table-extended :cols="cols">

              <tr v-for="(customer, counter) in customers" :data-item-id="customer.id" class="cursor-pointer"
                  :key="customer.id">
                <th scope="row">{{ row_counter(counter, pagination.current_page, pagination.per_page) }}</th>

                <td>{{ customer.full_name }}</td>
                <td>
                  <select :value="customer.status" class="form-select form-select-sm" name="user-status"
                          @change="(e) => onUserActivationStatusChange(e, customer.id)">
                    <option v-for="(status_name, status_val) in userActivationStatuses"
                            :value="status_val">
                      {{ status_name }}
                    </option>
                  </select>
                </td>
                <td>
                  <select :value="customer.role" class="form-select form-select-sm" name="user-role"
                          @change="(e) => onUserRoleChange(e, customer.id)">
                    <option v-for="(role_name, role_val) in roles" :value="role_val">
                      {{ role_name }}
                    </option>
                  </select>
                </td>
                <td>{{ dt_format(customer.created_at) }}</td>
                <td>
                  <router-link :to="url('customers.profile', { id: customer.id })">Смотреть</router-link>
                  |
                  <router-link :to="url('customers.edit', { id: customer.id })">Редактировать</router-link>
                </td>
              </tr>

            </q-table-extended>
          </q-loading>
        </div>

      </div>

      <div class="row">
        <pagination :pagination="pagination"/>
      </div>

    </template>
  </obertka-adminki>
</template>

<script>
import QLoading from "../../komponenti/QLoading.vue";
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import {servicePolzovatelei, usersService} from "../../api/index.js";
import QTable from "../../komponenti/QTable.vue";
import TablePagination from "../../komponenti/TablePagination.vue";
import Pagination from "../../komponenti/Pagination.vue";
import UserCardFragment from "./UserCardFragment.vue";
import NewAccountForm from "../../komponenti/akkaunti/SozdatNoviAkkauntForma.vue";
import {employeeService} from "../../api/ServisSotrudnikov.js";
import QTableExtended from "../../komponenti/QTableExtended.vue";
import {mapMutations} from "vuex";
import {Roli} from "../../utils/enums/Roli.js";

export default {
  name: "CustomersPage",
  components: {
    QTableExtended,
    NewAccountForm,
    UserCardFragment, Pagination, TablePagination, QTable, ObertkaAdminki, ZagolovokStranitc, QLoading
  },
  data() {
    return {
      customers: [],
      error: null,
      page: null,
      loading: false,
      cols: {
        name: 'Имя',
        status: 'Статус',
        role: 'Роль',
        created_at: 'Зарегистрирован',
        actions: 'Действия'
      },
      userActivationStatuses: {
        '1': 'Активирован',
        '0': 'Не активирован'
      },
      roles: {
        // '1': 'Админ',
        '2': 'Менеджер',
        '3': 'Курьер',
        '4': 'Кладовщик',
        '5': 'Клиент',
        '6': 'Оператор',
      },
      pagination: null,
      usernameText: '',
      //view_type: '',
      userCreationFormOpen: false,
      redirect_route: '',

      delivery_points: []
    }
  },
  async mounted() {
    await this.getCustomers();

    const {action, redirect} = this.$route.query;
    this.redirect_route = redirect;

    if (action === 'customer.add') {
      this.userCreationFormOpen = true;
    }
  },

  computed: {},

  methods: {

    ...mapMutations(['ADD_COURIER']),

    onCreateCustomer(customer) {
      this.switchLoading();
      servicePolzovatelei()
          .sozdatKlienta(customer)
          .then(response => {
            if (201 === response.status) {
              this.customers = [...this.customers, response.data];

              if (this.redirect_route) {
                this.$router.push({name: this.redirect_route});
              } else {
                this.toggleUserCreationForm();
              }
            }
          })
          .catch(e => {
            this.error = e?.response?.data?.message;
          })
          .finally(() => {
            this.switchLoading();
          });
    },

    toggleUserCreationForm() {
      this.userCreationFormOpen = !this.userCreationFormOpen;
    },

    async onUsernameSearch() {

      const searchTerm = this.usernameText.trim();

      return searchTerm !== ''
          ? await this.searchUsers(this.usernameText)
          : await this.getCustomers();
    },

    async searchUsers(username) {
      try {
        const {data} = await servicePolzovatelei().naitiPokupatelia(username);
        this.customers = data.data;
        this.pagination = data;
      } catch (e) {
        console.error(e);
      }

    },

    async getCustomers() {
      try {
        this.loading = true;
        const {data} = await servicePolzovatelei().paginate(this.$route.query);
        this.customers = data.data;
        this.pagination = data;
      } catch (e) {
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

      const role = +e.target.value;

      try {
        const {data} = await employeeService().updateRole(role, user_id);

        if (data.result) {
          if (role === Roli.Courier) {
            const user = this.customers.find(c => c.id === user_id);
            this.ADD_COURIER({id: user_id, value: user.full_name});
          }

          this.customers = this.customers.filter(c => c.id !== user_id);
        }

      } catch (e) {
        alert(e);
      }
    },
  }
}
</script>

<style scoped>

</style>
