<template>
  <div id="main">
    <sidebar />
    <div class="myContainer">
      <welcome title="Overall System Logs" :src="iconBlush" />
      <div class="miniContainer">
        <v-text-field label="Search" v-model="search" />
        <v-data-table
          :search="search"
          height="400px"
          :headers="headers"
          :items="logs"
          :items-per-page="5"
          class="elevation-1"
        ></v-data-table>
      </div>
    </div>
  </div>
</template>

<script>
import iconBlush from "../assets/icons/iconBlush.svg";
import Welcome from "./base/welcome.vue";
import Sidebar from "./base/sidebar.vue";
import axios from "axios";
import token from "../dev/token";

export default {
  components: { Sidebar, Welcome },
  data: () => ({
    iconBlush,
    search: "",
    headers: [
      {
        text: "Action Type",
        value: "type",
      },
      { text: "Remarks", value: "remarks" },
      { text: "Timestamp", value: "created_at" },
    ],
    logs: [],
  }),
  async created() {
    const response = await axios.get("/api/logs", token());
    this.logs = response.data;
  },
};
</script>
