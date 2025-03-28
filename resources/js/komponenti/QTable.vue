
<template>
  <div class="table-responsive table-wrapper">
    <table class="table table-responsive table-hover">
      <thead>
      <tr class="table-header">
        <th scope="row">#</th>
        <th v-for="col of cols" scope="col">{{ col }}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(row, counter) in rows" :data-item-id="row[id_name]" class="cursor-pointer" :key="counter"
          @click="onRowClick">
        <th scope="row">{{ ++counter }}</th>
        <slot :item="row" name="item"/>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "QTable",
  props: {
    cols: {
      type: Object,
    },
    rows: {
      type: Array,
    },
    id_name: {
      type: String,
      default: 'id'
    }
  },
  emits: ['onRowClick'],
  methods: {
    onRowClick(e) {
      this.$emit('onRowClick', e)
    }
  }
}
</script>

<style scoped>

table {
  position: relative;
}

th {
  position: sticky;
  top: 0;
}
</style>
