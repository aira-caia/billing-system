<template>
  <div class="main">
    <h1>{{ title }} <img :src="src" alt="icon" /></h1>
  </div>
</template>

<script>
export default {
  props: ["title", "src"],
};
</script>

<style scoped>
h1 {
  font-size: 2rem;
    font-weight: bold;
    font-family: "Bergen Sans";
}
.main {
  width: 220px;
  display: flex;
  position: relative;
}
img {
  position: relative;
  max-height: 48px;
  bottom: 0;
}
</style>
