import vuetify from "./plugins/vuetify"
import router from "./plugins/router"

window.Vue = require('vue').default;
Vue.component('app', require('./app.vue').default);


const app = new Vue({
    el: '#app',
    vuetify,
    router
});
