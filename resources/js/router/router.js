import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from "../views/login.vue"
import Settings from "../views/settings.vue"
import Menu from "../views/menu.vue"
import home from "../views/home.vue"
import middlewares from "../middlewares/index"

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        component: Login,
        name: "login",
        meta: {
            middlewares: [middlewares.guest]
        }
    },
   /* {
        path: '/settings',
        component: Settings,
        name: "settings",
        meta: {
            middlewares: [middlewares.auth]
        }
    },*/
    {
        path: '/menu',
        component: Settings,
        name: "menu",
        meta: {
            middlewares: [middlewares.auth]
        }
    },
    {
        path: '/home',
        component: home,
        name: "home",
        meta: {
            middlewares: [middlewares.auth]
        }
    },

]

function nextCheck(context, middlewares, index) {
    const nextMiddleware = middlewares[index]

    if (!nextMiddleware) return context.next

    return (...parameters) => {
        context.next(...parameters)
        const nextMidd = nextCheck(context, middlewares, index + 1)

        nextMiddleware({ ...context, next: nextMidd })
    }
}

const router = new VueRouter({ routes, mode: 'history' })
router.beforeEach((to, from, next) => {
    if (to.meta.middlewares) {
        const middlewares = to.meta.middlewares

        const context = { to, from, next, router }

        const nextMiddleware = nextCheck(context, middlewares, 1)

        return middlewares[0]({ ...context, next: nextMiddleware })
    }

    return next()
})
export default router;
