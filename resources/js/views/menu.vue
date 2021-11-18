<template>
    <div id="main">
        <sidebar/>
        <div class="myContainer">
            <welcome title="Welcome to Menu" :src="iconDelicious"/>
            <img :src="handIcon" class="handIcon" alt=""/>
            <div class="miniContainer">
                <categories :handler="handleCategory" :removable="false"/>
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
import categories from "./base/categories.vue";
import Menus from "./base/menus.vue";
import Sidebar from "./base/sidebar.vue";
import Welcome from "./base/welcome.vue";
import iconDelicious from "../assets/icons/iconDelicious.svg";
import handIcon from "../assets/icons/Saly-25.svg";
import axios from "axios";
import Notification from "./Notification";

export default {
    components: {Notification, Menus, categories, Sidebar, Welcome},
    data: () => ({
        iconDelicious,
        handIcon,
        category: {value: "All", title: "All"},
        menus: []
    }),
    watch: {
        category(){
            this.getMenus()
        }
    },
    created(){
      this.getMenus()
    },
    methods: {
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
    top: 0;
    right: 0;
    width: 297px;
}
</style>
