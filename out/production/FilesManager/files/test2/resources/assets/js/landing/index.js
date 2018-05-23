import '../bootstrap'
import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

Vue.component('landing', require('./Landing'));

const app = new Vue({
    el: '#app'
})



