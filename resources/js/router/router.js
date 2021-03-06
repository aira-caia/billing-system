import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from "../views/login.vue"
import Reports from "../views/Reports.vue"
import Menu from "../views/settings.vue"
import Logs from "../views/Logs.vue"
import home from "../views/home.vue"
import profile from "../views/profile.vue"
import Transaction from "../views/Transaction.vue"
import middlewares from "../middlewares/index"
import NotFound from "../views/404.vue";
import PrintSales from "../views/reports/PrintSales";
import PrintPurchase from "../views/reports/PrintPurchase";
import PrintTransaction from "../views/reports/PrintTransaction";
import About from "../views/About";
Vue.use(VueRouter)

// Based on what is typed, in our search/url bar, we are redirected on that page
const routes = [
    {
        path: '/',
        component: Login,
        name: "login",
        meta: {
            middlewares: [middlewares.guest]
        }
    },
    { path: "*", component: NotFound },
    {
        path: '/menu',
        component: Menu,
        name: "menu",
        meta: {
            middlewares: [middlewares.auth]
        }
    },
    {
        path: '/logs',
        component: Logs,
        name: "logs",
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
    {
        path: '/about',
        component: About,
        name: "about",
    },
    {
        path: '/reports',
        component: Reports,
        name: "reports",
        meta: {
            middlewares: [middlewares.auth]
        }
    },
    {
        path: '/sales',
        component: PrintSales,
        name: "sales",
        meta: {
            middlewares: [middlewares.auth]
        }
    },
    {
        path: '/interactions',
        component: PrintSales,
        name: "interactions",
        meta: {
            middlewares: [middlewares.auth]
        }
    },
    {
        path: '/purchases',
        component: PrintSales,
        name: "purchases",
        meta: {
            middlewares: [middlewares.auth]
        }
    },
    {
        path: '/profile',
        component: profile,
        name: "profile",
        meta: {
            middlewares: [middlewares.auth]
        }
    },
    {
        path: '/transaction',
        component: Transaction,
        name: "transaction",
        meta: {
            middlewares: [middlewares.auth]
        }
    },
    {
        path: '/transaction-report',
        component: PrintTransaction,
        name: "transaction-report",
        meta: {
            middlewares: [middlewares.auth]
        }
    },
    {
        path: '/product-report',
        component: PrintPurchase,
        name: "product-report",
        meta: {
            middlewares: [middlewares.auth]
        }
    },
    {
        path: '/purchases-report',
        component: PrintTransaction,
        name: "purchases-report",
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
