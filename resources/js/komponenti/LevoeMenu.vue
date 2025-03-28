/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <div class="sidebar col-md-3 col-lg-2 p-0 ae-bg">

    <dashboard-svgs/>

    <div id="sidebarMenu" aria-labelledby="sidebarMenuLabel" class="offcanvas-md offcanvas-end" tabindex="-1">
      <div class="offcanvas-header">
        <h5 id="sidebarMenuLabel" class="offcanvas-title">arash.express</h5>
        <button aria-label="Close" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"
                type="button"></button>
      </div>
      <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">

        <ul class="navbar-nav">
          <template v-for="route in firstRoutes" :key="route.name">
            <li class="nav-item">
              <span v-if="route.meta.isParent"
                    class="nav-link d-flex align-items-center gap-2 text-white menu-item__header">
                 <svg class="bi">
                  <use v-bind="{'xlink:href': route.meta.useSvg }"></use>
                </svg>
                {{ route.meta.caption }}
              </span>
              <router-link
                  v-else-if="!route.meta.isChild"
                  class="nav-link d-flex align-items-center gap-2"
                  :class="{'active': isActivePage(route.name), 'menu-item__header': route.children.length > 0}"
                  :to="route.path"
                  aria-current="page">
                <svg class="bi">
                  <use v-bind="{'xlink:href': route.meta.useSvg }"></use>
                </svg>
                {{ route.meta.caption }}
              </router-link>

              <template v-if="route.children.length > 0">
                <ul class="ps-0 submenu">
                  <li v-for="child in sidebarMenuItems(route.children)" :key="route.name">

                    <router-link
                        class="nav-link d-flex align-items-center gap-2"
                        :class="{'active': isActivePage(child.name) }"
                        :to="route.path + '/' + child.path"
                        aria-current="page">
                      <svg class="bi">
                        <use v-bind="{'xlink:href': child.meta.useSvg }"></use>
                      </svg>
                      {{ child.meta.caption }}
                    </router-link>
                  </li>
                </ul>

              </template>
            </li>
          </template>
        </ul>

        <hr class="my-3">

        <ul class="nav flex-column mb-auto">

          <li class="nav-item">
            <span class="nav-link dark-mode-switcher-container">
                <switcher id="darkModeSwitcher" v-model:checked="isDarkMode"
                          class="d-flex align-items-center">
                    <div class="ms-2 dark-mode-switcher-text">
                        {{ isDarkMode ? 'Ночной режим' : 'Дневной режим' }}
                    </div>
                </switcher>
            </span>
          </li>

          <li v-for="route in secondRoutes" :key="route.name" class="nav-item">
            <router-link
                :class="{'nav-link d-flex align-items-center gap-2': true, 'active': isActivePage(route.name) }"
                :to="`/${route.name}`">
              <svg class="bi">
                <use v-bind="{'xlink:href': route.meta.useSvg }"></use>
              </svg>
              {{ route.meta.caption }}
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardSvgs from "./IkonkiPrilojenia.vue";
import router from "../router";
import {mapActions, mapGetters, mapState} from "vuex";
import Switcher from "./Perekluchatel.vue";
import {sideBarMenuToggler} from "../utils/sideBarMenuToggler.js";


export default {
  name: "AppSidebar",
  components: {Switcher, DashboardSvgs},
  data() {
    return {
      firstRoutes: [],
      secondRoutes: [],
      isCollapsed: false
    }
  },
  computed: {
    ...mapGetters('authStore', ['user']),
    ...mapState('appSettingsStore', ['unreadPostsCount', 'isDarkModeEnabled']),

    isDarkMode: {
      get() {
        return this.isDarkModeEnabled;
      },
      set(value) {
        this.setDarkMode(value);
      }
    }
  },

  mounted() {

    const routes = router.getRoutes();


    this.firstRoutes = routes.filter(route => {
      if (
          route && route.name &&
          route.meta && route.meta.isSidebarItem &&
          !['logout', 'profile'].includes(route.name) &&
          route.meta.role.includes(this.user.user_role)
      ) {
        return route;
      }
    });


    this.firstRoutes.sort(this.compareRoutesOrder);

    this.secondRoutes = routes.filter(route => {
          if (route && route.meta && route.meta.isSidebarItem && ['logout', 'profile'].includes(route.name))
            return route;
        }
    );

    this.secondRoutes.sort(this.compareRoutesOrder);

    sideBarMenuToggler();

  },

  methods: {

    ...mapActions('appSettingsStore', ['setDarkMode']),

    route_allowed(route) {
      return route.meta.role.includes(this.user.user_role);
    },

    /**
     *
     * @param r1 {RouteRecord}
     * @param r2 {RouteRecord}
     * @return {number}
     */
    compareRoutesOrder(r1, r2) {
      if (r1.meta.order < r2.meta.order) {
        return -1;
      }
      if (r1.meta.order > r2.meta.order) {
        return 1;
      }
      return 0;
    },

    isActivePage(route_name) {
      return this.$route.name === route_name;
    },

    /**
     *
     * @param items {Array}
     */
    sidebarMenuItems(items) {
      return items.filter(item => item?.meta?.isSidebarItem && this.route_allowed(item));
    }
  }
}
</script>

<style scoped>

.menu-item__header {
  cursor: pointer;
  color: #000 !important;
}

[data-bs-theme="dark"] .menu-item__header {
  color: #fff !important;
}

.menu-item__header::after {
  font-family: "Bootstrap-icons", serif;
  content: "\F282";
  padding: 0 18px;
  margin-left: auto;

  transition: transform 0.3s linear;
}

.menu-item__header-open::after {
  font-family: "Bootstrap-icons", serif;
  content: "\F282";
  padding: 0 18px;
  transform: rotate(180deg);
}

.submenu {
  padding-left: 0;
  transition: max-height 0.3s ease-in-out;
  max-height: 0;
  overflow: hidden;
  opacity: .95;
}

@media (max-width: 767px) {
  .submenu {
    max-height: 100%;
  }
}

.menu-item__header-open + .submenu {
  max-height: 500px;
}

[data-bs-theme="dark"] .sidebar {
  border-right: 1px solid #121212;
}

.active {
  background: var(--ae-primary);
}

.nav-item a.active {
  color: #fff !important;
}

.nav-item a {
  color: var(--bs-white);
}

.navbar-nav .nav-item a, span {
  padding-left: 18px;
}


.nav-item .nav-link {
  transition: padding-left 0.15s linear;
}

.nav-item .nav-link:hover {
  padding-left: 25px;
  background: var(--ae-primary);
  color: #fff !important;
}

.ae-bg {
  background-color: #f8f8f8;
}

.ae-bg a {
  color: #000 !important;
}

[data-bs-theme="dark"] .ae-bg {
  background: var(--bs-dark);
}

[data-bs-theme="dark"] .ae-bg a {
  color: #fff !important;
}

.dark-mode-switcher-text {
  margin-top: 4px;
  color: var(--bs-dark) !important;
}

.dark-mode-switcher-container:hover .dark-mode-switcher-text {
  color: #fff !important;
}

[data-bs-theme="dark"] .dark-mode-switcher-text {
  color: #fff !important;
}


</style>
