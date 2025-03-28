/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
    <nav v-show="pagination !== null" aria-label="Table posts pagination">
        <ul class="pagination justify-content-end">
            <li v-for="link in pagination?.links" class="page-item">
                <router-link :class="{'page-link': true, active: link.active}" :to="link.url">{{
                        link.label
                    }}
                </router-link>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    name: "TablePagination",
    props: {
        pagination: {
          type: Object,
        }
    },

    mounted() {

    },

}
</script>

<style scoped>

</style>
