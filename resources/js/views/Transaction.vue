<template>
  <div id="main">
    <sidebar />
    <div class="myContainer">
      <div class="mb-4">
        <v-btn
          color="primary"
          text
          small
          :to="{ name: 'reports' }"
          class="mb-2"
        >
          <v-icon small>mdi-cash-register</v-icon>
          View Sales</v-btn
        >
        <v-alert outlined color="success">
          <div class="text-h6 mb-2">Generate Report</div>
          <div class="font-weight-bold mb-2">Transactions</div>
          <div>
            <v-btn color="primary" outlined small @click="report('daily')">
              <v-icon small>mdi-format-list-text</v-icon>
              Daily
            </v-btn>
            <v-btn color="primary" outlined small @click="report('weekly')">
              <v-icon small>mdi-format-list-text</v-icon>
              Weekly
            </v-btn>
            <v-btn color="primary" outlined small @click="report('monthly')">
              <v-icon small>mdi-format-list-text</v-icon>
              Monthly
            </v-btn>
            <v-btn color="primary" outlined small @click="report('yearly')">
              <v-icon small>mdi-format-list-text</v-icon>
              Yearly
            </v-btn>
          </div>
          <div class="font-weight-bold my-2">Purchases</div>
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
      </div>
      <template>
        <v-card>
          <v-card-title>
            Transactions
            <v-btn color="primary" class="ml-2" small @click="printPayments()"
              >Print</v-btn
            >
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>
          <v-data-table
            @click:row="handleClick"
            height="360px"
            :headers="headers"
            :items="payments"
            item-key="id"
            :search="search"
          ></v-data-table>
        </v-card>
      </template>
      <div class="mt-5">
        <template>
          <v-card>
            <v-card-title>
              Purchases on selected order
              <v-btn
                color="primary"
                class="ml-2"
                small
                @click="printPayments('purchases-report')"
                >Print</v-btn
              >
              <v-spacer></v-spacer>
              <v-text-field
                v-model="searchPurchase"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
              ></v-text-field>
            </v-card-title>

            <v-data-table
              height="360px"
              :headers="purchaseHeaders"
              :items="purchases"
              :search="searchPurchase"
            ></v-data-table>
          </v-card>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import sidebar from "./base/sidebar.vue";
import token from "../dev/token";
export default {
  components: { sidebar },
  data: () => ({
    search: "",
    searchPurchase: "",
    selectedOrder: "",
    headers: [
      {
        text: "ID",
        align: "start",
        value: "id",
      },
      // { text: "Order Code", value: "order_code" },
      { text: "Receipt Number", value: "receipt_number" },
      { text: "Payment Type", value: "type" },
      { text: "Amount (Peso)", value: "total" },
      { text: "Date Paid", value: "paid_at" },
    ],
    purchaseHeaders: [
      {
        text: "Menu",
        align: "start",
        value: "title",
      },
      { text: "Item Count", value: "count" },
      { text: "Total Amount (Peso)", value: "amount" },
      { text: "Ingredients", value: "ingredients" },
    ],
    type: "daily",
    payments: [],
    purchases: [],
  }),
  created() {
    axios.get("/api/payments/v2", token()).then((r) => {
      this.payments = r.data.data;
    });
  },
  methods: {
    handleClick(value) {
      this.getPurchase(value.receipt_number);
    },
    report(val) {
      axios
        .get("/api/report/transact", { ...token(), params: { type: val } })
        .then((r) => {
          localStorage.setItem("payments", JSON.stringify(r.data));
          let routeData = this.$router.resolve({
            name: "transaction-report",
          });
          window.open(routeData.href, "_blank");
        });
    },
    purchase(val) {
      axios
        .get("/api/report/purchase", { ...token(), params: { type: val } })
        .then((r) => {
          localStorage.setItem("purchases", JSON.stringify(r.data));
          let routeData = this.$router.resolve({
            name: "purchases-report",
          });
          window.open(routeData.href, "_blank");
        });
    },
    printPayments(type = "payments") {
      if (type === "payments") {
        localStorage.setItem("payments", JSON.stringify(this.payments));
        let routeData = this.$router.resolve({
          name: "transaction-report",
        });
        window.open(routeData.href, "_blank");
      } else {
        localStorage.setItem("purchases", JSON.stringify(this.purchases));
        let routeData = this.$router.resolve({
          name: "purchases-report",
        });
        window.open(routeData.href, "_blank");
      }
    },
    getPurchase(code) {
      axios
        .get(`/api/receipt/v2/${code}`, {
          headers: {
            Authorization:
              "Bearer $2y$10$tmoxPjspNPUZvXDUsMg.huWw4RGsaA.aiivrKs1kOhafxaMubAAZ.",
          },
        })
        .then((r) => {
          this.purchases = r.data.orders;
        });
    },
  },
};
</script>

<style>
</style>
