/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>
    <zagolovok-stranitc :caption="$route.meta.caption">
      <button
          :class="{'btn btn-sm btn-outline-warning': !_filterBlockVisible, 'btn btn-sm btn-warning': _filterBlockVisible }"
          @click="toggleFiltering">
        <i class="bi bi-funnel-fill" v-if="_filterBlockVisible"></i>
        <i class="bi bi-funnel" v-else></i>
        Фильтр
      </button>
    </zagolovok-stranitc>

    <div class="mx-1 my-3" v-show="_filterBlockVisible">
      <div class="row gy-2">
        <div class="col-md-3">
          <label class="form-label">Пользователи</label>
          <multiselect placeholder="Выберите пользователей..." :options="usernames" v-model="_selectedUsers"/>
        </div>
        <div class="col-md-3">
          <label class="form-label">Действие</label>
          <multiselect placeholder="Выберите действия..." :options="actions" v-model="_selectedActions"/>
        </div>
        <div class="col-md-3">
          <label class="form-label">Начало</label>
          <input type="datetime-local" class="form-control form-control-sm" v-model="_startDatetime"/>
        </div>
        <div class="col-md-3">
          <label class="form-label">Конец</label>
          <input type="datetime-local" class="form-control form-control-sm" v-model="_endDatetime"/>
        </div>
      </div>
      <div class="row mt-3 gx-2 gy-2">
        <div class="col-md-auto">
          <input type="button" value="Получить" class="btn btn-sm btn-success" @click="onFilter"/>
        </div>
        <div class="col-md flex-grow-1">
          <input type="button" value="Очистить фильтр" class="btn btn-sm btn-warning" @click="onFilterClear"/>
        </div>
      </div>
    </div>

    <q-table :cols="cols" :rows="rows">
      <template #item="{item}">
        <td>
          <router-link :to="profile(item)">{{ item.full_name }}</router-link>
        </td>
        <td>{{ tr(item.action_name) }}</td>
        <td>{{ item.os }}</td>
        <td>{{ item.browser }}</td>
        <td>{{ item.client_ip }}</td>
        <!-- <td>{{ item.data }}</td> -->
        <td>{{ item.url }}</td>
        <td>
          <router-link :to="item.referer">{{ item.referer }}</router-link>
        </td>
        <td>{{ dt_format2(item.created_at) }}</td>
      </template>
    </q-table>

    <div class="row overflow-x-auto">
      <pagination :pagination="pagination"/>
    </div>

  </obertka-adminki>
</template>

<script>
import ObertkaAdminki from "../komponenti/ObertkaAdminki.vue";
import ZagolovokStranitc from "../komponenti/ZagolovokStranitc.vue";
import QTable from "../komponenti/QTable.vue";
import {logsService} from "../api/ServisDeistviiPolzovatelei.js";
import FileUpload from "../komponenti/ZagruzkaFileKnopka.vue";
import ChooseFilesDialog from "../komponenti/ChooseFilesDialog.vue";
import Pagination from "../komponenti/Pagination.vue";
import get_translation, {activity_log_action_types} from "../utils/activity_log_action_type_translations.js";
import Multiselect from "../komponenti/Multiselect.vue";
import {mapMutations, mapState} from "vuex";
import {employeeService} from "../api/ServisSotrudnikov.js";
import {Roli} from "../utils/enums/Roli.js";


export default {
  name: "UsersActivityLogsPage",
  components: {Multiselect, Pagination, ChooseFilesDialog, FileUpload, QTable, ZagolovokStranitc, ObertkaAdminki},
  data: () => ({
    rows: [],
    cols: {
      name: "ФИО",
      action_name: "Действие",
      os: "ОС",
      browser: "Браузер",
      client_ip: "Ip",
      //data: "Мета",
      url: "URL",
      referer: "Откуда",
      created_at: "Дата",
    },
    pagination: null,
    value: '',
    usernames: [],
    unique_ips: [],
    unique_urls: [],
  }),

  async mounted() {

    const {data} = await logsService().filter(this.$route.query, this.getFilterData());
    this.rows = data.data;
    this.pagination = data;

    if (this._filterBlockVisible && !this.usernames.length && !this.unique_ips.length && !this.unique_urls.length) {
      await this.fetchFilterOptions();
    }
  },

  computed: {

    ...mapState('usersActivityLogsStore', [
      'filterBlockVisible', 'selectedUsers',
      'selectedActions', 'startDatetime', 'endDatetime',
      'filterIPs', 'filterUrls'
    ]),

    _filterUrls: {
      get() {
        return this.filterUrls;
      },
      set(value) {
        this.SET_FILTER_URLS(value);
      }
    },

    _filterIPs: {
      get() {
        return this.filterIPs;
      },
      set(value) {
        this.SET_FILTER_IPS(value);
      }
    },

    _selectedUsers: {
      get() {
        return this.selectedUsers;
      },
      set(value) {
        this.SET_SELECTED_USERS(value);
      }
    },

    _selectedActions: {
      get() {
        return this.selectedActions;
      },
      set(value) {
        this.SET_SELECTED_ACTIONS(value);
      }
    },

    _startDatetime: {
      get() {
        return this.startDatetime;
      },
      set(value) {
        this.SET_FILTER_START_DATETIME(value);
      }
    },

    _endDatetime: {
      get() {
        return this.endDatetime;
      },
      set(value) {
        this.SET_FILTER_END_DATETIME(value);
      }
    },

    _filterBlockVisible: {
      get() {
        return this.filterBlockVisible;
      },
      set(value) {
        this.SET_FILTER_BLOCK_VISIBILITY(value);
      }
    },

    actions() {
      const list = [];
      for (const action in activity_log_action_types) {
        list.push({id: action, value: activity_log_action_types[action]});
      }

      return list;
    },
  },


  methods: {

    ...mapMutations('usersActivityLogsStore', [
      'SET_FILTER_BLOCK_VISIBILITY',
      'SET_SELECTED_USERS', 'SET_SELECTED_ACTIONS',
      'SET_FILTER_START_DATETIME', 'SET_FILTER_END_DATETIME',
      'SET_FILTER_IPS', 'SET_FILTER_URLS',
      'FILTER_CLEAR',
    ]),

    async fetchFilterOptions() {
      const res = await employeeService().getAll();
      this.usernames = res.data.data.map(user => ({id: user.id, value: `${user.full_name}`}));
    },

    async toggleFiltering() {
      this._filterBlockVisible = !this._filterBlockVisible;

      if (this._filterBlockVisible && !this.usernames.length && !this.unique_ips.length && !this.unique_urls.length) {
        await this.fetchFilterOptions();
      }
    },

    tr(key) {
      return get_translation(key, 'unknown');
    },

    profile(item) {
      return this.$router.resolve({
        name: item.user_role === Roli.Customer ? 'customers.profile' : 'employee.profile',
        params: {id: item.user_id}
      })
    },

    getFilterData() {
      return {
        users: this.selectedUsers.map(u => u.id),
        actions: this.selectedActions.map(a => a.id),
        ips: this.filterIPs.map(i => i.value),
        urls: this.filterUrls.map(i => i.value),
        start_dt: this.startDatetime && Date.parse(this.startDatetime),
        end_dt: this.endDatetime && Date.parse(this.endDatetime),
      };
    },

    async onFilter() {
      const rdata = this.getFilterData();
      const {data} = await logsService().filter(this.$route.query, rdata);
      this.rows = data.data;
      this.pagination = data;
    },

    onFilterClear() {
      this.FILTER_CLEAR();
    },

  },
}
</script>

<style scoped>

</style>
