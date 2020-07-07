/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueProgressBar from 'vue-progressbar'
import VueRouter from 'vue-router'
import { Form, HasError, AlertError } from 'vform'
import {routes} from './routes'

window.form = Form;

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

const options = {
  color: 'green',
  failedColor: 'red',
  thickness: '2px',
  transition: {
    speed: '0.5s',
    opacity: '0.6s',
    termination: 3000
  }
}
Vue.use(VueRouter);
const router = new VueRouter({
  routes, // short for `routes: routes`
  mode: 'history'
})
// router.beforeEach((to, from, next) => {
  // this.$Progress.finish();
  // alert('yesss');
//  next()
// })
Vue.use(VueProgressBar, options);

// Vue.use(Form);
const app = new Vue({
  el: '#app',
  router
});
