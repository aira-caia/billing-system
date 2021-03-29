<template>
  <ul class="categories">
    <li class="category activeCategory">
      <!-- <v-icon class="removeCategory" color="error">mdi-minus</v-icon> -->
      <img :src="iconAll" alt="icon" />
      <span>Pizza</span>
    </li>
    <li class="category">
      <v-icon class="removeCategory" v-if="removable" color="error"
        >mdi-minus</v-icon
      >
      <img :src="iconAll" alt="icon" />
      <span>Pizza</span>
    </li>
    <li class="category">
      <v-icon class="removeCategory" v-if="removable" color="error"
        >mdi-minus</v-icon
      >
      <img :src="iconAll" alt="icon" />
      <span>Pizza</span>
    </li>
    <li class="category">
      <v-icon class="removeCategory" v-if="removable" color="error"
        >mdi-minus</v-icon
      >
      <img :src="iconAll" alt="icon" />
      <span>Pizza</span>
    </li>
    <li class="nextCategory">
      <v-icon class="nextIcon" x-large>mdi-chevron-right</v-icon>
    </li>
  </ul>
</template>

<script>
import iconAll from "../../assets/icons/iconAll.svg";

export default {
  props: {
    removable: { type: Boolean, default: true },
  },
  data() {
    return {
      iconAll,
    };
  },
};
</script>

<style scoped>
.category {
  display: flex;
  flex-direction: column;
  background: #f5f6f7;
  max-width: 88px;
  width: 100%;
  align-items: center;
  height: 164px;
  justify-content: center;
  border-radius: 55px;
  position: relative;
  margin-right: 40px;
  cursor: pointer;
}
.categories {
  display: flex;
  margin-top: 35px;
}
.category img {
  max-width: 60px;
}
.category span {
  font-size: 14px;
  font-weight: bold;
  margin-top: 10px;
}
ul {
  padding: 0;
}
.removeCategory {
  position: absolute;
  top: 10px;
  right: -5px;
  border: 1px solid red;
  cursor: pointer;
}
.removeCategory:hover::before {
  background: red;
  color: #fff;
}
.activeCategory {
  background: #ffffff;
  box-shadow: 0 27px 30px rgba(0, 0, 0, 0.1);
}
.nextCategory {
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
}
</style>