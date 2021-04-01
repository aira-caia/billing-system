<template>
    <ul class="categories">
        <li class="category" @click="toggleCategory(category)" v-for="category in categories" :class="{activeCategory: category.active}">
<!--            <v-icon class="removeCategory" v-if="removable" color="error"
            >mdi-minus
            </v-icon
            >-->
            <img :src="category.icon" alt="icon"/>
            <span v-text="category.title"></span>
        </li>
        <!--        <li class="category">
                    <v-icon class="removeCategory" v-if="removable" color="error"
                    >mdi-minus
                    </v-icon
                    >
                    <img :src="beerIcon" alt="icon"/>
                    <span>Beer</span>
                </li>-->
        <li class="nextCategory">
            <v-icon disabled class="nextIcon" x-large>mdi-chevron-right</v-icon>
        </li>
    </ul>
</template>

<script>
import iconAll from "../../assets/icons/iconAll.svg";
import breakfastIcon from "../../assets/icons/icons8_sunny_side_up_eggs_1.svg"
import lunchIcon from "../../assets/icons/icons8_steak.svg"
import dinnerIcon from "../../assets/icons/icons8_fish_food.svg"
import dessertIcon from "../../assets/icons/icons8_cake.svg"
import drinksIcon from "../../assets/icons/icons8_lemonade.svg"
import alcoholIcon from "../../assets/icons/icons8_wine_glass.svg"
import beerIcon from "../../assets/icons/icons8_beer.svg"

export default {
    props: {
        removable: {type: Boolean, default: true},
        handler: {type: Function}
    },
    data() {
        return {
            categories: [
                {
                    icon: iconAll, title: "All", value: "All", active: true
                },   {
                    icon: breakfastIcon, title: "Breakfast", value: 4, active: false
                }, {
                    icon: lunchIcon, title: "Lunch", value: 1, active: false
                }, {
                    icon: dinnerIcon, title: "Dinner", value: 2, active: false
                }, {
                    icon: dessertIcon, title: "Desserts", value: 3, active: false
                }, {
                    icon: drinksIcon, title: "Drinks", value: 5, active: false
                }, {
                    icon: alcoholIcon, title: "Alcohol", value: 6, active: false
                }, /*{
                    icon: beerIcon, title: "Beer", value: 7, active: false
                },*/
            ]
        };
    },
    methods: {
        toggleCategory(category){
            this.categories.forEach(category => category.active = false)
            category.active = true
            this.handler(category)
        }
    }
};
</script>

<style scoped>
.category {
    display: flex;
    flex-direction: column;
    background: #f5f6f7;
    width: 88px;
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
    width: 60px;
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
