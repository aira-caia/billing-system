import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from "../views/login.vue"
import Settings from "../views/settings.vue"
import Menu from "../views/menu.vue"
import home from "../views/home.vue"
Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        component: Login,
        name: "login"
    },
    {
        path: '/settings',
        component: Settings,
        name: "settings"
    },
    {
        path: '/menu',
        component: Menu,
        name: "menu"
    },
    {
        path: '/home',
        component: home,
        name: "home"
    },

]

const router = new VueRouter({ routes, mode: 'history' })

export default router;