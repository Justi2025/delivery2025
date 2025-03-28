/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <div class="table-responsive table-wrapper">
    <table class="table table-responsive table-hover">
      <thead>
      <tr>
        <th scope="row">#</th>
        <th v-for="col of cols" scope="col">{{ col }}</th>
      </tr>
      </thead>
      <tbody>
        <slot/>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "QTableExtended",
  props: {
    cols: {
      type: Object,
    },
  },

}
</script>

<style scoped>
thead {
  background: #0b5ed7;
  color: white;
}

tbody tr:hover {
  background: #f4f4f4;
}

tbody tr {

}
</style>
