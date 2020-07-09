/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import {routes} from './routes'

window.form = Form;
import moment from 'moment'
import Vue from 'vue'
import VueRouter from 'vue-router'
import VueProgressBar from 'vue-progressbar'
import { Form, HasError, AlertError } from 'vform'
import Swal from 'sweetalert2'
import VueChatScroll from 'vue-chat-scroll'
import { Vuetable, VuetablePagination } from 'vuetable-2'
import Select2 from 'v-select2-component';
// import Gate from './Gate';
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
import Datepicker from 'vuejs-datepicker';

Vue.component('v-select', vSelect)
Vue.component('Select2', Select2);
Vue.component('vuetable', Vuetable)
Vue.component('vuetable-pagination', VuetablePagination)
// Vue.prototype.$gate = new Gate(window.user);
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.use(VueRouter)
Vue.use(VueProgressBar, options)
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.use(Datepicker)
Vue.use(Vuetable)
Vue.use(VueChatScroll)

//
let Trigger = new Vue;
const options = {
  color: '#bffaf3',
  failedColor: 'red',
  thickness: '5px',
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'left',
  inverse: false
}

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

window.Toast = Toast
window.Vue = require('vue');
window.Form = Form
window.Swal = require('sweetalert2')
window.Trigger = Trigger

Vue.filter('textTransform', function(value){
  if (!value) return ''
  value = value.toString()
  return value.charAt(0).toUpperCase() + value.slice(1)
});
Vue.filter('Capitalize', function(value){
  if (!value) return ''
  value = value.toString()
  return value.toUpperCase()
});
Vue.filter('dateChange', function(value){
  return moment(value).format('MMMM Do YYYY');
});
Vue.use(VueRouter);
const router = new VueRouter({
  routes, // short for `routes: routes`
  mode: 'history'
})
router.beforeEach((to, from, next) => {
  // alert(to.name);
 next()
})
Vue.use(VueProgressBar, options);
export const dataCheck = new Vue();
// Vue.use(Form);
const app = new Vue({
  el: '#app',
  router
});
