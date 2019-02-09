// import router from './routes';

// Vue.component('flash', require('./components/flash'));

require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import {routes} from './routes';
import StoreData from './store';
import MainApp from './components/MainApp.vue';
import {initialize} from './helpers/general';
import * as VueGoogleMaps from 'vue2-google-maps';

import BootstrapVue from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue);

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyCSX-pplGo7Lrq8jbsNNa5Az0sZFzNunno',
    libraries: 'places',
  },
});

Vue.component('GRecaptcha', require('./components/GRecaptcha'));
Vue.component('DatePicker', require('./components/DatePicker'));
Vue.component('TimePicker', require('./components/TimePicker'));
Vue.component('BaseMap', require('./components/BaseMap'));
Vue.component('GoogleMap', require('./components/GoogleMap'));


const store = new Vuex.Store(StoreData);

const router = new VueRouter({
    routes,
    mode: 'history'
});

initialize(store, router);

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        MainApp
    }
});

