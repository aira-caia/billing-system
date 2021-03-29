<template>
  <div id="main">
    <sidebar />
    <div class="myContainer">
      <welcome title="Welcome to Menu" :src="iconDelicious" />
      <img :src="handIcon" class="handIcon" alt="" />
      <div class="miniContainer">
        <categories :removable="false" />
      </div>
      <div class="miniContainer d-flex justify-space-between">
        <span class="myBtn">
          Pizza
          <button class="myBtn">
            <v-icon class="btnIcon ml-2">mdi-sort-bool-ascending</v-icon>
          </button>
        </span>
      </div>
      <div class="miniContainer">
        <menus />
      </div>
    </div>
  </div>
</template>

<script>
import categories from "./base/categories.vue";
import menus from "./base/menus.vue";
import Sidebar from "./base/sidebar.vue";
import Welcome from "./base/welcome.vue";
import iconDelicious from "../assets/icons/iconDelicious.svg";
import handIcon from "../assets/icons/Saly-25.svg";

export default {
  components: { menus, categories, Sidebar, Welcome },
  data: () => ({
    iconDelicious,
    handIcon,
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
.handIcon {
  position: absolute;
  top: 0;
  right: 0;
  width: 297px;
}
</style>
