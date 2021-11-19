<template>
  <div class="ma-5">
    <div v-if="$route.name === 'sales'">
      <div class="mb-5 d-flex justify-content-center">
        <h3 class="hTitle text-center" id="monthlySales">SALES REPORT</h3>
      </div>
      <MonthlySales :payload="monthlySales" />
      <div class="my-5"></div>
      <WeeklySales :payload="weeklySales" />
    </div>
    <div v-else-if="$route.name === 'interactions'">
      <div class="my-5 d-flex justify-content-center">
        <h3 class="hTitle text-center" id="monthlyInteractions">
          INTERACTIONS REPORT
        </h3>
      </div>
      <MonthlyInteraction :payload="monthlyInteractions" />
      <weekly-interaction :payload="weeklyInteractions" />
    </div>
    <div v-else-if="$route.name === 'purchases'">
      <div class="my-5 d-flex justify-content-center">
        <h3 class="hTitle text-center" id="monthlyPurchase">
          PRODUCT PURCHASE REPORT
        </h3>
      </div>
      <monthly-sold :payload="monthlySold" />
      <weekly-sold :payload="weeklySold" />
    </div>
  </div>
</template>

<script>
import MonthlyInteraction from "./MonthlyInteraction";
import WeeklyInteraction from "./WeeklyInteraction";
import MonthlySales from "./MonthlySales";
import WeeklySales from "./WeeklySales";
import MonthlySold from "./MonthlySold.vue";
import WeeklySold from "./WeeklySold.vue";
import axios from "axios";
import token from "../../dev/token";

export default {
  name: "PrintSales",
  data: () => ({
    monthlySales: {},
    weeklySales: {},
    monthlyInteractions: {},
    weeklyInteractions: {},
    monthlySold: {},
    weeklySold: {},
    loaded: false,
  }),
  components: {
    WeeklySales,
    MonthlySales,
    MonthlyInteraction,
    WeeklyInteraction,
    MonthlySold,
    WeeklySold,
  },
  watch: {
    loaded(val) {
      if (val) {
        setTimeout(() => {
          window.print();
        }, 2000);
      }
    },
  },
  mounted() {
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
        this.loaded = true;
      });
    },
  },
};
</script>

<style scoped>
</style>
