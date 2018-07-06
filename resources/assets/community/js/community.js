require('./bootstrap');
import Vue from 'vue'
import router from './router/index'
import App from './components/App'
import VeeValidate from 'vee-validate'

import {fetchData} from "./helpers";

Vue.use(VeeValidate, {inject : true});

axios.get('/api/user/profile?include=roles')
  .then(fetchData)
  .then(user => {
    Vue.prototype.$authenticatedUser = user;

    new Vue({
      el: '#community',
      router,
      render: h => h(App)
    });
  })
  .catch(console.error);

