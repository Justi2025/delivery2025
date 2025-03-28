
<template>
  <nav aria-label="пагинация" v-if="isVisible">
    <ul class="pagination pagination-sm justify-content-center justify-content-md-end">
      <li :class="{'page-item': true, active: link.active}" v-for="link in pagination?.links">
        <router-link class="page-link" :to="removeApiPrefix(link.url)" v-html="link.label"></router-link>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: "Pagination",
  props: {
    pagination: {
      type: Object,
    }
  },

  mounted() {

  },

  computed: {
    isVisible() {
      return this.pagination?.total > 15;
    }
  },

  methods: {

    /**
     * @param url {string}
     * @return {*}
     */
    removeApiPrefix(url) {
      if (!url) return '#';
      return url?.replace(import.meta.env.VITE_APP_URL_API, '');
    },
  }
}
</script>

<style scoped>
.pagination>li>a,
.pagination>li>span {
  border: 0;
  color: var(--bs-warning);
}
.pagination>li.active>a {
  background: var(--bs-warning);
  color: #fff !important;
  border-radius: 50% 40%;
}
</style>
