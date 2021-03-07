import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

import './assets/app.scss';

Vue.config.productionTip = false

Vue.directive('date', {
  bind: function (el, binding) {
    const date = new Date(el.innerText);
    if (binding.arg === 'time'){
      el.innerText = date.getDate() + "/" + (date.getMonth()+1) + "/" + date.getFullYear() + " " + date.getHours() + ":" + date.getMinutes();
    } else {
      el.innerText = date.getDate() + "/" + (date.getMonth()+1) + "/" + date.getFullYear();
    }
    
  }
});

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')