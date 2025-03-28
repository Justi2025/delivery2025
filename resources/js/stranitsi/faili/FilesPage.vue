
<template>
  <obertka-adminki>
    <zagolovok-stranitc caption="Загруженные файлы">
      <div>
        <view-type-switcher v-model="view_type"/>
      </div>
    </zagolovok-stranitc>

    <div class="row g-3" v-if="view_type === 'card'">
      <div class="col-md-2" v-for="file in files" :key="file.id">
        <file-card :file="file" @on-remove="onRemove" />
      </div>
    </div>

    <div class="table-responsive" v-else>
      <table class="table table-hover">
        <thead>
        <tr>
          <th scope="col">Id</th>
          <th scope="col">Название</th>
          <th scope="col">Тип</th>
          <th scope="col">Размер</th>
          <th scope="col">Создан</th>
          <th scope="col">Действия</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(file, counter) in files" :data-id="file.id" :data-name="file.original_name"
            class="cursor-pointer">
          <td>{{ ++counter }}</td>
          <td><a :href="'/upload/' + file.generated_name" target="_blank">{{ file.original_name }}</a></td>
          <td>{{ getFileExtension(file) }}</td>
          <td>{{ formatBytes(file.size) }}</td>
          <td>{{ dt_format2(file.created_at) }}</td>
          <!--<td>{{ username(file?.user) }}</td>
          <td>{{ file?.object_source }}</td>-->
          <td>
            <span class="text-primary file-remove" @click="removeFile">Удалить</span>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <div class="row">
      <pagination :pagination="pagination"/>
    </div>

  </obertka-adminki>
</template>

<script>
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import QTable from "../../komponenti/QTable.vue";
import {filesService} from "../../api/index.js";
import format_baitov from "../../utils/format_baitov.js";
import FileUpload from "../../komponenti/ZagruzkaFileKnopka.vue";
import ChooseFilesDialog from "../../komponenti/ChooseFilesDialog.vue";
import Pagination from "../../komponenti/Pagination.vue";
import ViewTypeSwitcher from "../sdelki/komponenti/PerekluchatelVida.vue";
import FileCard from "./KartochkaFaila.vue";


export default {
  name: "FilesPage",
  components: {
    FileCard,
    ViewTypeSwitcher, Pagination, ChooseFilesDialog, FileUpload, QTable, ZagolovokStranitc, ObertkaAdminki},
  data() {
    return {
      files: [],
      pagination: [],

      view_type: 'list',
    }
  },

  async mounted() {

    const {data} = await filesService.paginate(this.$route.query);
    this.files = data.data;
    this.pagination = data;
  },


  computed: {},

  methods: {

    onFilesAdded(files) {
      console.log(files);
    },

    getFileExtension(file) {
      return file.generated_name?.split('.')[1];
    },

    formatBytes(bytes, decimal) {
      return format_baitov(bytes, decimal);
    },

    async onRemove({id, name}) {
      if (!confirm(`Вы действительно хотите удалить ${name}?`)) return;

      try {
        const {data} = await filesService.remove(+id);
        this.files = this.files.filter(_file => +_file.id !== +id);
      } catch (error) {
        alert(error.message);
      }
    },

    async removeFile(e) {
      const file = e.target.closest('tr').dataset;

      if (!confirm(`Вы действительно хотите удалить ${file.name}?`)) return;

      try {
        const {data} = await filesService.remove(+file.id);
        this.files = this.files.filter(_file => +_file.id !== +file.id);
      } catch (error) {
        alert(error.message);
      }

    },

    onFileUpload(uploadedFile) {
      this.files = [...this.files, uploadedFile];
    }
  }
  ,
}
</script>

<style scoped>
.file-remove:hover {
  text-decoration: underline;
}
</style>
