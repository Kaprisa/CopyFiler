import './bootstrap'
import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import 'video.js/dist/video-js.css'

Vue.use(Vuetify)

//Vue.component('app', require('./components/App'));
import App from './components/App'

import store from './store'

import router from './router'

import './vue/filters'

import './auth'

const app = new Vue({
    el: '#app',
    components: {
        App
    },
    template: '<app/>',
    store,
    router,
})



