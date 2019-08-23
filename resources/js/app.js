/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'
import { Form, HasError, AlertError } from 'vform'
// Progress Bar

import VueProgressBar from 'vue-progressbar'

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '2px'
})
//------------------------------

// ES6 Modules or TypeScript
import Swal from 'sweetalert2'

// CommonJS
window.Swal = Swal

//Toast
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
})

window.Toast = Toast
//-------------------------------

window.Form=Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

window.Fire = new Vue();  //Fire is a global instance of Vue hence has access to $emit and $on

Vue.use(VueRouter)
import ExampleComponent from './components/ExampleComponent.vue'
import Dashboard from './components/Dashboard.vue'
import Profile from './components/Profile.vue'
import Users from './components/Users.vue'
import Developer from './components/Developer.vue'


let routes = [
    {path: '/check', component: ExampleComponent},
    {path: '/developer', component: Developer},
    {path: '/dashboard', component: Dashboard},
    {path: '/profile', component: Profile},
    {path: '/users', component: Users},
]

let router = new VueRouter({
  mode: 'history',
  routes
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Laravel Passport--------------------------------------------
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);
//------------------------------------------------------------------

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
