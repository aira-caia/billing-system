<template>
  <div class="main">
    <div class="profile-info">
      <div
        class="profileImg"
        :style="
          image_path
            ? 'background-image: url(' + image_path + ')'
            : 'background-image: url(' + display + ')'
        "
      ></div>
      <h3 class="font-weight-bold">Hi, {{ username }}!</h3>
    </div>
    <ul class="navigations">
      <li :class="{ active: $route.name === 'home' }" class="navItem">
        <v-icon class="navIcon">mdi-home-roof</v-icon>
        <router-link class="navText" to="/home">Home</router-link>
      </li>
      <li :class="{ active: $route.name === 'logs' }" class="navItem">
        <v-icon class="navIcon">mdi-calendar-text</v-icon>
        <router-link class="navText" to="/logs">Logs</router-link>
      </li>
      <!--    <li :class="{ active: $route.name === 'reports' }" class="navItem">
                      <v-icon class="navIcon">mdi-chart-bell-curve-cumulative</v-icon>
                      <router-link class="navText" to="/reports">Reports</router-link>
                  </li> -->
      <li :class="{ active: $route.name == 'menu' }" class="navItem">
        <v-icon class="navIcon">mdi-view-list</v-icon>
        <router-link class="navText" to="/menu">Menu</router-link>
      </li>
      <li
        :class="{ active: $route.name == 'profile' }"
        class="navItem"
        disabled
      >
        <v-icon class="navIcon">mdi-cog-outline</v-icon>
        <router-link class="navText" to="/profile">Edit Profile</router-link>
      </li>
      <!--      <li class="navItem" :class="{ active: $route.name === 'settings' }">
                    <v-icon class="navIcon">mdi-cog-outline</v-icon>
                    <router-link to="/settings" class="navText">Settings</router-link>
                  </li>-->
      <li class="navItem">
        <v-icon class="navIcon">mdi-toggle-switch-off-outline</v-icon>
        <button class="navText" @click="logout">Sign out</button>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from "axios";
import token from "../../dev/token";
import display from "../../assets/icons/store.svg";

export default {
  data: () => ({
    username: "Admin",
    image_path: null,
    display,
  }),
  methods: {
    logout() {
      axios.post("/api/logout", {}, token()).then((r) => {
        localStorage.removeItem("token");
        this.$router.push({ name: "login" });
      });
    },
    getUser() {
      axios.get("/api/user", token()).then((r) => {
        this.username = r.data.username;
        this.image_path = r.data.image_path;
        console.log(this.image_path);
      });
    },
  },
  created() {
    this.getUser();
  },
};
</script>

<style scoped>
* {
  font-family: "Bergen Sans", sans-serif;
  padding: 0;
  margin: 0;
  box-sizing: border-box;
  list-style: none;
}

.main {
  background: #e6ebfe;
  max-width: 240px;
  width: 100%;
  height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  position: fixed;
}

.profile-info {
  margin: 90px 0 30px 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.profile-info h3 {
  font-size: 1.2rem;
  margin-top: 12px;
}

.profile-info .profileImg {
  height: 90px;
  width: 90px;
  overflow: hidden;
  border: 5px solid #fff;
  border-radius: 25px;
  background-position: center;
  /* background-position-y: -10px; */
  object-fit: cover;
  background-repeat: no-repeat;
  background-size: cover;
}

.profileImg img {
  max-width: 80px;
}

.navigations {
  margin: 0;
  padding: 0;
}

.navigations li {
  height: 60px;
  width: 200px;
  overflow: hidden;
  margin: 14px 0;
}

.navItem .navIcon {
  background: #fff;
  font-size: 2rem;
  height: 100%;
  width: 70px;
  border-radius: 20px;
}

.navItem .navText {
  margin-left: 22px;
  font-size: 1rem;
  color: #9598a9;
  text-decoration: none;
  cursor: pointer;
}

.navItem .navIcon {
  color: #9598a9;
}

.navItem.active .navIcon {
  background: #ec6083;
  color: #fff;
}

.navItem.active {
  border-radius: 20px;
  background: #f87193;
}

.navItem.active .navText {
  color: #fff;
}
</style>


