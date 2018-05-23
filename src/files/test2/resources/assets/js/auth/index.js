import  Vue from 'vue'
import Auth from './Auth'
import axios from 'axios'
const token = document.getElementsByName('csrf-token')[0].getAttribute('content')

const instance = axios.create({
})
instance.defaults.headers.common['X-CSRF-Token'] = token
instance.defaults.headers.common['Accept'] = 'application/json'

Vue.prototype.$auth = new Auth(instance)
Vue.prototype.$axios = instance
