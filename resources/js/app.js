import vuetify from "./plugins/vuetify"
import router from "./router/router"

import LoadScript from 'vue-plugin-load-script';


window.Vue = require('vue').default;
Vue.use(LoadScript);
Vue.component('app', require('./app.vue').default);

const app = new Vue({
    el: '#app',
    vuetify,
    router
});
