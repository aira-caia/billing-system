<template>
    <div id="main">
        <add-menu :category="category" v-if="addMenuDialog" :reloadMenus="getMenus" :setMenuDialog="setMenuDialog" />
        <sidebar/>
        <div class="myContainer">
            <welcome title="Welcome to Menu" :src="iconLol"/>
            <img :src="handIcon" class="handIcon" alt=""/>
            <div class="miniContainer">
                <Categories :handler="handleCategory" :removable="false"/>
            </div>
            <div class="miniContainer d-flex justify-space-between">
                <span class="myBtn">
                  {{category.title}}
<!--                  <button class="myBtn">
                    <v-icon class="btnIcon ml-2">mdi-sort-bool-ascending</v-icon>
                  </button>-->
                </span>
                <button class="myBtn" @click="addMenuDialog = !addMenuDialog" v-if="category.value !== 'All'">
                    New item
                    <v-icon class="btnIcon ml-2" large>mdi-plus</v-icon>
                </button>
            </div>
            <div class="miniContainer">
                <Menus :menus="menus" :reloadMenus="getMenus"/>
            </div>
        </div>
    </div>
</template>

<script>
import sidebar from "./base/sidebar";
import welcome from "./base/welcome.vue";
import Categories from "./base/categories.vue";
import Menus from "./base/menus.vue";
import iconLol from "../assets/icons/iconLol.svg";
import AddMenu from "./base/addMenu.vue";
import axios from "axios";
import handIcon from "../assets/icons/Saly-25.svg";


export default {
    components: {
        sidebar,
        welcome,
        Categories,
        Menus,
        AddMenu,
    },
    data: () => ({
        iconLol,
        addMenuDialog: false,
        category: {value: "All", title: "All"},
        menus: [],
        handIcon
    }),
    watch: {
        category(){
            this.getMenus()
        }
    },
    methods: {
        setMenuDialog(state) {
            this.addMenuDialog = state;
        },
        getMenus() {
            axios.get('/api/menu',{params: {query: this.category.value}}).then(r => {
                const menus = r.data.data
                const lists = [];
                let indexer = 0;
                menus.forEach((menu,i) => {
                    if(i % 3 === 0 && i !== 0) indexer++

                    if(!lists[indexer]){
                        lists[indexer] = []
                    }
                    lists[indexer].push(menu)
                })
                this.menus = lists
            })
        },
        handleCategory(category) {
            this.category = category
        }
    },
    created() {
        this.getMenus();
    }
};
</script>

<style scoped>
* {
    font-family: "Bergen Sans", sans-serif;
    color: #353853;
}

.myBtn,
.btnIcon {
    font-size: 1.4rem;
    font-weight: bold;
    color: #353853;
    outline: none;
    border: none;
}

.btnIcon {
    outline: none;
}
.handIcon {
    position: absolute;
    top: -50px;
    right: 0;
    width: 297px;
}
</style>
