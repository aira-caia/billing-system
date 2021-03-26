<template>
  <div class="main">
    <div class="profile-info">
      <div class="profileImg"></div>
      <h3>Hi, Admin!</h3>
    </div>
    <ul class="navigations">
      <li class="navItem">
        <v-icon class="navIcon">mdi-home-roof</v-icon>
        <span>Home</span>
      </li>
      <li class="navItem">
        <v-icon class="navIcon">mdi-view-list</v-icon>
        <span>Menu</span>
      </li>
      <li class="navItem active">
        <v-icon class="navIcon">mdi-cog-outline</v-icon>
        <span>Settings</span>
      </li>
      <li class="navItem">
        <v-icon class="navIcon">mdi-toggle-switch-off-outline</v-icon>
        <span>Sign out</span>
      </li>
    </ul>
  </div>
</template>

<script>
export default {};
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
}
.main {
  max-width: 240px;
  height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
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
  height: 80px;
  width: 80px;
  overflow: hidden;
  border: 5px solid #fff;
  border-radius: 25px;
  background: url("https://images.pexels.com/photos/220453/pexels-photo-220453.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500");
  background-position: center;
  background-position-y: -10px;
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
  cursor: pointer;
}
.navItem .navIcon {
  background: #fff;
  font-size: 2rem;
  height: 100%;
  width: 70px;
  border-radius: 20px;
}
.navItem span {
  margin-left: 22px;
  font-size: 1rem;
  color: #9598a9;
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
.navItem.active span {
  color: #fff;
}
</style>