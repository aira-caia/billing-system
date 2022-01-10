<template>
  <div class="ma-5">
    <div v-if="$route.name === 'transaction-report'">
      <div class="mb-5 d-flex justify-content-center">
        <h3 class="hTitle text-center" id="monthlySales">TRANSACTION REPORT</h3>
      </div>
      <v-data-table
        :headers="headers"
        :items="payments"
        item-key="id"
        disable-pagination
        hide-default-footer
      ></v-data-table>
    </div>
    <div v-if="$route.name === 'purchases-report'">
      <div class="mb-5 d-flex justify-content-center">
        <h3 class="hTitle text-center" id="monthlySales">PURCHASES REPORT</h3>
      </div>
      <v-data-table
        :headers="purchaseHeaders"
        :items="purchases"
        disable-pagination
        hide-default-footer
      ></v-data-table>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
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
    purchases: [],
    payments: [],
  }),
  mounted() {
    if (this.$route.name === "transaction-report") {
      this.payments = JSON.parse(localStorage.getItem("payments"));
    } else {
      this.purchases = JSON.parse(localStorage.getItem("purchases"));
    }
    if (this.payments.length > 0 || this.purchases.length > 0) {
      setTimeout(() => {
        window.print();
      }, 2000);
    }
  },
};
</script>

<style>
@media print {
  body {
    overflow-x: hidden;
  }
  ::-webkit-scrollbar {
    display: none;
  }
}
</style>
