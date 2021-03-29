<template>
  <div>
    <div class="myCards">
      <div class="myCard">
        <img
          src="https://images.pexels.com/photos/1199956/pexels-photo-1199956.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
          alt="food_image"
        />
        <h3>Burger 'Combo'</h3>
        <p>
          Onions, A little garlic, Tuna, Egg, Broccoli, Spicy chicken, Soy sauce
        </p>
        <span>P395.00</span>
        <v-icon large class="removeMenu" color="white">mdi-minus</v-icon>
      </div>
      <div class="myCard">
        <img
          src="https://images.pexels.com/photos/6697455/pexels-photo-6697455.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
          alt="food_image"
        />
        <h3>Burger 'Combo'</h3>
        <p>
          Onions, A little garlic, Tuna, Egg, Broccoli, Spicy chicken, Soy sauce
        </p>
        <span>P395.00</span>
        <v-icon large class="removeMenu" color="white">mdi-minus</v-icon>
      </div>
      <div class="myCard">
        <img
          src="https://images.pexels.com/photos/6697455/pexels-photo-6697455.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
          alt="food_image"
        />
        <h3>Burger 'Combo'</h3>
        <p>
          Onions, A little garlic, Tuna, Egg, Broccoli, Spicy chicken, Soy sauce
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
        </p>
        <span>P395.00</span>
        <v-icon large class="removeMenu" color="white">mdi-minus</v-icon>
      </div>
    </div>
    <div class="myCards">
      <div class="myCard">
        <img
          src="https://images.pexels.com/photos/6697455/pexels-photo-6697455.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
          alt="food_image"
        />
        <h3>Burger 'Combo'</h3>
        <p>
          Onions, A little garlic, Tuna, Egg, Broccoli, Spicy chicken, Soy sauce
        </p>
        <span>P395.00</span>
        <v-icon large class="removeMenu" color="white">mdi-minus</v-icon>
      </div>
      <div class="myCard">
        <img
          src="https://images.pexels.com/photos/6697455/pexels-photo-6697455.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
          alt="food_image"
        />
        <h3>Burger 'Combo'</h3>
        <p>
          Onions, A little garlic, Tuna, Egg, Broccoli, Spicy chicken, Soy sauce
        </p>
        <span>P395.00</span>
        <v-icon large class="removeMenu" color="white">mdi-minus</v-icon>
      </div>
      <div class="myCard">
        <img
          src="https://images.pexels.com/photos/6697455/pexels-photo-6697455.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
          alt="food_image"
        />
        <h3>Burger 'Combo'</h3>
        <p>
          Onions, A little garlic, Tuna, Egg, Broccoli, Spicy chicken, Soy sauce
        </p>
        <span>P395.00</span>
        <v-icon large class="removeMenu" color="white">mdi-minus</v-icon>
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style scoped>
.myCard {
  border: 1px solid #a5a5a5;
  width: 100%;
  max-width: 270px;
  padding: 120px 30px 30px 30px;
  position: relative;
  border-radius: 45px;
}
.myCards {
  display: flex;
  justify-content: space-between;
  margin-bottom: 80px;
}
.myCard:hover {
  background: #efefef;
}
.myCard p {
  font-size: 12px;
  margin-top: 7px;
  font-weight: bold;
  margin-bottom: 55px;
}
.myCard h3 {
  font-size: 1.2rem;
}
.myCard span {
  font-weight: bold;
  font-size: 1.4rem;
  position: absolute;
  bottom: 30px;
}
.myCard img {
  border-radius: 50%;
  height: 132px;
  width: 132px;
  background-position: 50% 50%;
  background-repeat: no-repeat;
  object-fit: cover;
  background-size: cover;
  background: gray;
  position: absolute;
  top: 0;
  transform: translate(-50%, -40%);
  left: 50%;
  cursor: pointer;
}
.removeMenu {
  border-radius: 55%;
  background: #30334f;
  position: absolute;
  bottom: 20px;
  right: 20px;
  width: 50px;
  height: 50px;
  cursor: pointer;
}
.removeMenu:hover {
  background: red;
}
</style>