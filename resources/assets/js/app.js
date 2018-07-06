require('./bootstrap');

window.Vue = require('vue');

/*** INTEGRATE THE VUE-SCRIPTS AS COMPONENTS INTO THE LARAVEL APP ***/
Vue.component('next-component', require('./components/ExampleComponent.vue'));
Vue.component('next-market', require('./views/Markets.vue'));
Vue.component('next-wallet', require('./views/Wallet.vue'));
Vue.component('next-minepool', require('./views/Minepool.vue'));
Vue.component('next-ico', require('./views/ICO.vue'));
Vue.component('next-exchange', require('./views/Exchange.vue'));
Vue.component('next-orderbook', require('./views/Orderbook.vue'));
Vue.component('next-transactions', require('./views/Transactions.vue'));

import VueNotifications from 'vue-notification';
import VeeValidate from 'vee-validate';
import VueSweetAlert from 'vue-sweetalert2';
import VueClipBoard from 'vue-clipboard2';
import VueMoment from 'vue-moment';
import VueChatScroll from 'vue-chat-scroll-top-scroll'
import vSelect from 'vue-select';
import router from './router';
import App from './components/App.vue';
import VueResource from 'vue-resource';
import Vue2Filters from 'vue2-filters';

export const EventBus = new Vue();

Vue.use(VueResource);
Vue.use(VueNotifications);
Vue.use(VeeValidate);
Vue.use(VueSweetAlert);
Vue.use(VueClipBoard);
Vue.use(VueMoment);
Vue.use(VueChatScroll);
Vue.use(Vue2Filters);
Vue.component('v-select', vSelect);

Vue.http.options.emulateJSON = true;

Vue.http.interceptors.push((request, next) => {
    request.headers['Access-Control-Allow-Origin'] = '*';
    request.headers.set('X-CSRF-TOKEN', Laravel.csrfToken);

    next();
});

/*** EXCHANGE APP ***/
new Vue({
    el: '#app',
    data: {
        btcdeposit: true
    },
    router,
    render: h => h(App)
});