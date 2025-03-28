/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <div class="d-inline-block">
    <label :class="['btn btn-sm', this.class]" :for="id">
      {{ caption }}
      <progress v-show="loading" :value="loadingProgress" max="100"></progress>
    </label>
    <input :id="id" :accept="this.accept" :disabled="disabled" name="file" type="file" @change="uploadFile"/>
  </div>
</template>

<script>
import {filesService} from "../api";

export default {
  name: "FileUpload",
  emits: ['uploadFile', 'onUpload'],
  props: {
    id: {
      type: String,
      default: 'upload-file'
    },
    caption: {
      type: String,
      required: true
    },
    endpoint: {
      type: String,
      required: false,
      default: '/files/upload'
    },
    accept: {
      type: String,
      required: false,
      default: '*/*'
    },
    'class': {
      type: String,
      default: 'btn-outline-primary'
    },
    disabled: {
      type: Boolean,
      default: false
    },
    dest: {
      type: String,
      validator(value) {
        return ['uploads', 'avatars'].includes(value);
      }
    },
    source_id: {
      type: String,
      required: false
    },
  },
  data() {
    return {
      loading: false,
      loadingProgress: 0
    }
  },
  methods: {
    async uploadFile(e) {

      this.loading = true;

      const file = e.target.files[0];

      const formData = new FormData();
      formData.set('file', file);
      formData.set('dest', this.dest || 'uploads');
      formData.set('fileable_id', this.source_id || '');

      try {
        const res = await filesService.uploadFile(
            formData,
            progress => this.loadingProgress = progress,
            this.endpoint
        );

        const createdFile = {
          id: res.data.id,
          original_name: res.data.original_name,
          generated_name: res.data.generated_name,
          type: res.data.type,
          size: res.data.size,
          user: res.data.user,
          created_at: res.data.created_at,
          object_source: res.data.owner_type || 'files.form'
        };

        this.loading = false;
        this.$emit('uploadFile', createdFile);
        this.$emit('onUpload', createdFile);

        //e.target.value = null;

      } catch (err) {
        e.target.value = null; // this is needed as after error files list is still contains value of file
        //console.log('file.upload', e.target.files);
        alert(err.response.data.message || err.response.statusText);
      } finally {
        this.loading = false;
        this.loadingProgress = 0;
      }
    }
  }
}
</script>

<style scoped>

div {

}

.ml-10px {
  margin-left: 10px;
}

label[for="file-update"] {
  cursor: context-menu;
}

input[type='file'] {
  /* opacity: 0;*/
  display: none;
  position: absolute;
  z-index: -1;
}

progress {
  display: block;
  height: 10px;
  width: 100%;
}

</style>
