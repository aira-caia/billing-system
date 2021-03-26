<template>
  <div class="d-flex">
    <div class="leftPanel">
      <div class="title">
        <h3 class="logo">caia.</h3>
        <sub>An application that will satisfy your taste!</sub>
      </div>
      <img :src="sally" alt="Girl With Phone" />
    </div>
    <div class="rightPanel">
      <div class="signInBox">
        <img class="handIcon" :src="sally8" alt="Hand" />
        <img class="phoneIcon" :src="sally12" alt="Hand" />
        <div class="title">
          <h2>Sign in</h2>
          <sub>Sign in now to manage your restaurant!</sub>
        </div>
        <form>
          <div class="inputGroup">
            <label for="username">USERNAME</label>
            <input type="text" id="username" placeholder="..." />
          </div>
          <div class="inputGroup">
            <label for="password">PASSWORD</label>
            <input type="password" id="password" placeholder="..." />
          </div>
          <button type="submit">Login</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import sally from "../assets/icons/Saly-14.svg";
import sally8 from "../assets/icons/Saly-8.svg";
import sally12 from "../assets/icons/Saly-12.svg";

export default {
  name: "Login",
  data: () => ({
    sally,
    sally8,
    sally12,
  }),
};
</script>

<style >
@font-face {
  font-family: "Bergen Sans";
  src: local("Bergen Sans"),
    url("../assets/fonts/BergenSans.ttf") format("truetype");
}

* {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
  text-decoration: none;
  font-family: "Bergen Sans";
}

@media screen and (min-width: 720px) {
  .leftPanel {
    width: 50%;
    height: 100vh;
    background-color: #e6ebfe;
    overflow: hidden;
  }
  .logo {
    font-weight: bold;
    font-size: 55px;
    font-family: "Bergen Sans", sans-serif;
    color: #ab97f3;
  }
  .leftPanel .title sub {
    font-family: "Bergen Sans", sans-serif;
    font-size: 1.2rem;
  }
  .leftPanel .title {
    padding: 40px 50px;
  }
  .leftPanel img {
    margin: 0 50px;
  }

  .rightPanel {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 50%;
  }

  .signInBox {
    background: #ab97f3;
    min-width: 562px;
    color: #fff;
    padding: 50px 40px;
    border-radius: 30px;
    position: relative;
  }
  .signInBox .title {
    margin-bottom: 90px;
  }
  .rightPanel .inputGroup {
    display: flex;
    flex-direction: column;
    margin: 10px 0 25px 0;
  }
  .rightPanel .inputGroup input {
    font-size: 24px;
    color: #fff;
    margin-top: 7px;
    outline: none;
    border: none;
    background: none;
  }
  .rightPanel .inputGroup input:focus {
    border-bottom: 1.5px solid #fff;
  }
  .rightPanel form button {
    width: 165px;
    background: #f87193;
    height: 45px;
    border-radius: 55px;
    outline: none;
  }
  .rightPanel .handIcon {
    position: absolute;
    bottom: 0;
    right: 0;
  }
  .rightPanel .phoneIcon {
    position: absolute;
    top: -80px;
    right: 0;
  }
}
</style>