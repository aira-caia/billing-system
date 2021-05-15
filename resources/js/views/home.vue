<template>
  <div id="main">
    <sidebar />
    <div class="myContainer">
      <welcome title="Welcome to Dashboard" :src="iconBlush" />
      <img :src="manIcon" class="manIcon" alt="manIcon" />
      <img :src="welcomeIcon" class="welcomeIcon" alt="" />
      <div class="miniContainer">
        <div class="cardRow">
          <div class="myCardSmall">
            <h3>Food served</h3>
            <img :src="iconPot" class="iconPot" alt="icon_pot" />
            <div class="total">
              <strong>Total</strong> <br />
              <span>{{ payload["serve"] }}</span>
            </div>
          </div>
          <div class="myCardSmall">
            <h3>Sold</h3>
            <img :src="iconCart" class="iconPot" alt="icon_pot" />
            <div class="total">
              <strong>Total</strong> <br />
              <span>{{ payload["serve"] }}</span>
            </div>
          </div>
        </div>
        <div class="cardRow">
          <div class="myCardMedium">
            <h3>Revenue</h3>
            <img :src="iconCoins" class="iconMed" alt="icon_pot" />
            <div class="total">
              <strong>Total</strong> <br />
              <span
                ><v-icon>mdi-currency-php</v-icon>
                {{ payload["revenue"] }}</span
              >
            </div>
          </div>
          <div class="myCardMedium">
            <h3>Transactions</h3>
            <img :src="iconCashier" class="iconMed" alt="icon_pot" />
            <div class="total">
              <strong>Total</strong> <br />
              <span>{{ payload["transactions"] }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import categories from "./base/categories.vue";
import menus from "./base/menus.vue";
import Sidebar from "./base/sidebar.vue";
import Welcome from "./base/welcome.vue";
import iconBlush from "../assets/icons/iconBlush.svg";
import welcomeIcon from "../assets/icons/Saly-6.svg";
import iconPot from "../assets/icons/iconPot.svg";
import iconCart from "../assets/icons/iconCart.svg";
import iconCoins from "../assets/icons/iconCoins.svg";
import iconCashier from "../assets/icons/iconCashier.svg";
import manIcon from "../assets/icons/Saly-11.svg";
import axios from "axios";
import token from "../dev/token";

export default {
  components: { menus, categories, Sidebar, Welcome },
  data: () => ({
    iconBlush,
    welcomeIcon,
    iconPot,
    iconCart,
    iconCoins,
    iconCashier,
    manIcon,
    payload: {},
  }),
  created() {
    axios.get("/api/home", token()).then((r) => {
      this.payload = r.data;
    });
  },
};
</script>

<style scoped>
* {
  font-family: "Bergen Sans", sans-serif;
  color: #353853;
}

.welcomeIcon {
  position: absolute;
  top: -50px;
  right: 0;
  width: 204px;
}
.manIcon {
  position: absolute;
  bottom: -200px;
  right: -120px;
  width: 320px;
}
.cardRow {
  display: flex;
  margin: 25px 0;
}
.myCardSmall {
  background: #fff;
  box-shadow: 0 0 26px 2px rgba(0, 0, 0, 0.08);
  border-radius: 20px;
  height: 135px;
  width: 357px;
  position: relative;
  padding: 25px 35px;
  margin-right: 40px;
}
.myCardMedium {
  background: #fff;
  box-shadow: 0 0 26px 2px rgba(0, 0, 0, 0.08);
  border-radius: 20px;
  height: 290px;
  width: 357px;
  position: relative;
  padding: 25px 35px;
  margin-right: 40px;
}
.myCardMedium .iconMed {
  position: absolute;
  bottom: 10px;
  right: 30px;
}
.myCardSmall .iconPot {
  width: 92px;
  position: absolute;
  right: 30px;
  top: 15px;
}
.total {
  margin-top: 15px;
}
.total strong {
  color: #9598a9;
}
</style>
