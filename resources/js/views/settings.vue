<template>
  <div id="main">
    <sidebar />
    <div class="myContainer">
      <welcome title="Welcome to Settings" :src="iconLol" />
      <div class="miniContainer">
        <button class="myBtn">
          New Category <v-icon class="btnIcon ml-2" large>mdi-plus</v-icon>
        </button>
        <Categories />
      </div>
      <div class="miniContainer d-flex justify-space-between">
        <span class="myBtn">
          Pizza
          <button class="myBtn">
            <v-icon class="btnIcon ml-2">mdi-sort-bool-ascending</v-icon>
          </button>
        </span>
        <button class="myBtn">
          New item <v-icon class="btnIcon ml-2" large>mdi-plus</v-icon>
        </button>
      </div>
      <div class="miniContainer">
        <Menus />
      </div>
    </div>
  </div>
</template>

<script>
import sidebar from "./base/sidebar";
import welcome from "./base/welcome.vue";
import Categories from "./base/categories.vue";
import Menus from "./base/menus.vue";
import iconLol from "../assets/icons/iconLol.svg";

export default {
  components: {
    sidebar,
    welcome,
    Categories,
    Menus,
  },
  data: () => ({
    iconLol,
  }),
};
</script>

<style scoped>
* {
  font-family: "Bergen Sans", sans-serif;
  color: #353853;
}
.myBtn,
.btnIcon {
  font-size: 1.4rem;
  font-weight: bold;
  color: #353853;
  outline: none;
  border: none;
}
.btnIcon {
  outline: none;
}
</style>
