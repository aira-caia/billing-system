<template>
  <div id="main">
    <sidebar />
    <div class="myContainer">
      <div class="p-3">
        <v-btn
          color="primary"
          class="mb-2"
          text
          small
          :to="{ name: 'transaction' }"
        >
          <v-icon small>mdi-cart</v-icon>
          View Transactions</v-btn
        >

        <div class="mb-5 d-flex justify-content-center">
          <h3 class="hTitle text-center" id="monthlySales">SALES REPORT</h3>
        </div>
        <MonthlySales :payload="monthlySales" />
        <WeeklySales :payload="weeklySales" />
        <div class="my-5 d-flex justify-content-center">
          <h3 class="hTitle text-center" id="monthlyInteractions">
            INTERACTIONS REPORT
          </h3>
        </div>
        <MonthlyInteraction :payload="monthlyInteractions" />
        <weekly-interaction :payload="weeklyInteractions" />

        <div class="my-5 d-flex justify-content-center">
          <h3 class="hTitle text-center" id="monthlyPurchase">
            PRODUCT PURCHASE REPORT
          </h3>
        </div>
        <v-alert outlined color="success" class="my-2">
          <div class="text-h6 mb-2">Purchases</div>
          <div>
            <v-btn color="primary" outlined small @click="purchase('daily')">
              <v-icon small>mdi-format-list-text</v-icon>
              Daily
            </v-btn>
            <v-btn color="primary" outlined small @click="purchase('weekly')">
              <v-icon small>mdi-format-list-text</v-icon>
              Weekly
            </v-btn>
            <v-btn color="primary" outlined small @click="purchase('monthly')">
              <v-icon small>mdi-format-list-text</v-icon>
              Monthly
            </v-btn>
            <v-btn color="primary" outlined small @click="purchase('yearly')">
              <v-icon small>mdi-format-list-text</v-icon>
              Yearly
            </v-btn>
          </div>
        </v-alert>
        <monthly-sold :payload="monthlySold" />
        <weekly-sold :payload="weeklySold" />
        <!--                <WeeklySales :payload="weekly"/>-->
      </div>
    </div>
  </div>
</template>

<script>
import MonthlySales from "./reports/MonthlySales.vue";
import WeeklySales from "./reports/WeeklySales.vue";
import axios from "axios";
import token from "../dev/token";
import Sidebar from "./base/sidebar";
import MonthlyInteraction from "./reports/MonthlyInteraction.vue";
import WeeklyInteraction from "./reports/WeeklyInteraction.vue";
import MonthlySold from "./reports/MonthlySold.vue";
import WeeklySold from "./reports/WeeklySold.vue";

export default {
  components: {
    MonthlyInteraction,
    Sidebar,
    WeeklySales,
    MonthlySales,
    ReportBar: MonthlySales,
    WeeklyInteraction,
    MonthlySold,
    WeeklySold,
  },
  data: () => ({
    monthlySales: {},
    weeklySales: {},
    monthlyInteractions: {},
    weeklyInteractions: {},
    monthlySold: {},
    weeklySold: {},
  }),
  created() {
    this.setupMonthly();
  },
  methods: {
    setupMonthly() {
      axios.get("/api/report", token()).then((r) => {
        this.monthlySales = r.data.monthlySales;
        this.weeklySales = r.data.weeklySales;
        this.monthlyInteractions = r.data.monthlyInteractions;
        this.weeklyInteractions = r.data.weeklyInteractions;
        this.monthlySold = r.data.monthlySold;
        this.weeklySold = r.data.weeklySold;
      });
    },
    purchase(val) {
      axios
        .get("/api/report/purchase/group", {
          ...token(),
          params: { type: val },
        })
        .then((r) => {
          localStorage.setItem("products", JSON.stringify(r.data));
          let routeData = this.$router.resolve({
            name: "product-report",
          });
          window.open(routeData.href, "_blank");
        });
    },
  },
};
</script>

<style scoped>
@import "https://adminlte.io/themes/v3/dist/css/adminlte.min.css";

.v-sheet--offset {
  top: -24px;
  position: relative;
}

.hTitle {
  font-weight: 300;
  width: 400px;
  border-bottom: 4px solid #6cb2eb;
}
</style>
<!--

S2 = 213,500

S1 = 201,000

G = (213,500 – 201,000)/201,000 * 100

G = (12,500)/201,000 * 100

G = 0.0622 * 100

G = 6.2 percent -->
