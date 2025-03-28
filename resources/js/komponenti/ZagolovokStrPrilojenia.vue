
<template>
  <header class="navbar sticky-top flex-md-nowrap p-0 shadow" data-bs-theme="dark">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white fw-bold" href="#">
      ArashExpress
    </a>

    <div class="text-white px-3 fw-bolder welcome-text">👋 {{ username }} <!--🖐--></div>

    <ul class="navbar-nav flex-row d-md-none">
      <li class="nav-item text-nowrap">
        <button aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation"
                class="nav-link px-3 text-white" data-bs-target="#sidebarMenu" data-bs-toggle="offcanvas"
                type="button">
          <svg class="bi">
            <use xlink:href="#list"></use>
          </svg>
        </button>
      </li>
    </ul>

  </header>
</template>

<script>
import store from "../store/index.js";

export default {
  name: "AppHeader",

  computed: {
    username() {
      if (store.getters['authStore/user'] !== null) {
        const {full_name} = store.getters['authStore/user'];
        const [, firstname,] = full_name.split(' ');
        return `${firstname}`;
      }

    }
  }
}
</script>

<style scoped>

header {
  background: var(--ae-primary);
}

@media screen and (max-width: 576px) {
  .welcome-text {
    font-size: .95rem;
  }
}

@media (max-width: 767px) {

  .navbar {
    padding-top: 5px !important;
    padding-bottom: 5px !important;
  }

  .navbar-brand {
    display: none;
  }
}

</style>
