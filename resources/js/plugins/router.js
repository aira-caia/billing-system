import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from "../views/home.vue"
import Settings from "../views/settings.vue"

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        component: Login
    },
    {
        path: '/settings',
        component: Settings
    },

]

const router = new VueRouter({ routes, mode: 'history' })

export default router;