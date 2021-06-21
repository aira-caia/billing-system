<template>
  <div id="main">
    <sidebar />
    <div class="myContainer">
      <template>
        <v-card>
          <v-card-title>
            Transactions
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
