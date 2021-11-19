<template>
  <v-app>
    <router-view></router-view>
  </v-app>
</template>

<script>
export default {
  name: "app",
};
</script>

<style>
@media print {
  @page {
    size: landscape;
    scale: 50;
  }
}
.myContainer {
  margin: 70px 90px 0 344px;
  position: relative;
}
@media screen and (min-width: 1400px) {
  .myContainer {
    margin: 70px 10vw 0 344px;
  }
}

@media screen and (min-width: 1500px) {
  .myContainer {
    margin: 70px 25vw 0 344px;
  }
}

@media print {
  .hideOnPrint {
    display: none;
  }
}
* {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
  list-style: none;
  outline: none;
}
.miniContainer {
  margin: 75px 0;
}

.alertBox {
  position: fixed;
  right: 100px;
  z-index: 99;
}
</style>
