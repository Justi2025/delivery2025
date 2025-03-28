/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
    <modal-dialog :id="targetId" ref="booksListDialog" title="Выберите файл">

        <div class="mt-2 mb-4">
            <div class="input-group mb-3">
                <input v-model="filename" aria-describedby="button-clear-input" aria-label="Название файла"
                       class="form-control" placeholder="Введите название файла..."
                       type="text"
                       @keyup="debouncedSearch">
                <button id="button-clear-input" class="btn btn-sm btn-outline-primary" type="button"
                        @click="clearSearch">
                    Очистить
                </button>
            </div>
        </div>

        <div v-show="chosenFiles.length > 0" class="mb-4">
            <p class="fw-bold">Выбранные файлы</p>
            <ul class="list-group overflow-y-auto py-1" style="max-height: 200px">
                <li v-for="chosenFile in chosenFiles" class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div>{{ chosenFile.original_name }}</div>
                        <div :data-file-id="chosenFile.id" class="cursor-pointer px-2 text-danger" @click="removeFile">
                            Убрать
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <table v-if="searchResult.length > 0"
               id="foundFiles" class="table table-hover table-responsive overflow-x-scroll">
            <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">Название</th>
                <th class="text-center" scope="col">Выбрать</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(file, counter) in searchResult" :data-generated="file.generated_name" :data-id="file.id"
                :data-original="file.original_name" class="cursor-pointer">
                <td>{{ ++counter }}</td>
                <td><label :for="file.generated_name">{{ file.original_name }}</label></td>
                <td class="text-center">
                    <!--@change="fileChosen"-->
                    <input v-model="chosenFiles" :value="file" class="form-check-input border-secondary"
                           type="checkbox"/>
                </td>
            </tr>
            </tbody>
        </table>

        <div v-else-if="emptySearchResult">
            <h4 class="h6">По запросу <b>{{ filename }}</b> не найдено ни одного файла</h4>
        </div>

        <template #footer>
            <file-upload caption="Загрузить файл" class="btn btn-sm btn-warning" @upload-file="onFileUpload"/>
            <button class="btn btn-sm btn-primary" data-bs-dismiss="modal" @click="addFiles">Добавить файлы</button>
        </template>

    </modal-dialog>
</template>

<script>
import ModalDialog from "./ModalDialog.vue";
import debounce from "../utils/debounce.js";
import {filesService} from "../api/index.js";
import FileUpload from "./ZagruzkaFileKnopka.vue";


export default {
    name: "ChooseFilesDialog",
    components: {FileUpload, ModalDialog},
    emits: ['onFilesAdded'],
    props: {
        targetId: {
            type: String,
            required: true
        }
    },
    data: () => ({
        chosenFiles: [],
        filename: '',
        searchResult: [],
        debouncedSearch: null,
    }),
    created() {
        // @see https://stackoverflow.com/questions/53041171/lodashs-debounce-not-working-in-vue-js
        this.debouncedSearch = debounce(this.search, 500);
    },

    mounted() {
        /*document.querySelector('#foundFiles').addEventListener('keydown', (e) => {
            if (e.key.toLowerCase() === 'a' && e.ctrlKey) {
                // Add your code here
                alert('S key pressed with ctrl');
            }
        });*/
    },

    computed: {
        emptySearchResult() {
            return this.filename.length !== 0 && this.searchResult.length === 0;
        }
    },

    methods: {


        clearSearch() {
            this.chosenFiles = [];
            this.filename = '';
            this.searchResult = [];
        },

        removeFile(e) {
            const fileId = +e.target.dataset.fileId;
            this.chosenFiles = this.chosenFiles.filter(f => +f.id !== +fileId);
        },

        async search(e) {

            const filename = e.target.value?.trim();

            if (0 === filename.length) {
                this.searchResult = [];
                return;
            }

            try {
                const {data} = await filesService.search(filename);
                this.searchResult = data;
            } catch (e) {
                //console.error(e)
                alert(e);
            }

        },

        onFileUpload(file) {
            this.chosenFiles = [...this.chosenFiles, file];
        },

        addFiles() {
            this.$emit('onFilesAdded', this.chosenFiles);
            this.clearSearch();
        }
    },
}
</script>

<style scoped>

</style>
