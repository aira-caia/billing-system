<template>
  <div class="ma-5">
    <div v-if="$route.name === 'product-report'">
      <div class="mb-5 d-flex justify-content-center">
        <h3 class="hTitle text-center" id="monthlySales">
          PRODUCT SALES REPORT
        </h3>
      </div>
      <v-data-table
        :headers="headers"
        :items="data"
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
      { text: "Menu", value: "title" },
      { text: "Qty (Sold)", value: "count" },
      { text: "Total Amount (Peso)", value: "amount" },
    ],
    data: [],
  }),
  mounted() {
    this.data = JSON.parse(localStorage.getItem("products"));
    console.log(this.data);
    setTimeout(() => {
      window.print();
    }, 2000);
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
