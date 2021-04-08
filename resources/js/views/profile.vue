<template>
  <div id="main">
    <sidebar />
    <div class="myContainer">
      <welcome title="Welcome to Dashboard" :src="iconBlush" />
      <!-- <img :src="manIcon" class="manIcon" alt="manIcon" /> -->
      <!-- <img :src="welcomeIcon" class="welcomeIcon" alt="" /> -->
    </div>
  </div>
</template>

<script>
import Sidebar from "./base/sidebar.vue";
import iconBlush from "../assets/icons/iconBlush.svg";

export default {
  components: { Sidebar },
  data: () => ({
    iconBlush,
  }),
};
</script>
