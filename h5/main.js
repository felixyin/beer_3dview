import Vue from 'vue'
import App from './App'
import $commonJs from './common/js/common.js'
import $utils from './common/js/utils.js'
import $customerService from './components/customer-service.vue'
import $settingItem from './components/setting-item'
import $btnCollections from './components/btn-collections'

Vue.config.productionTip = false
Vue.prototype.commonJs = $commonJs
Vue.prototype.utils = $utils
Date.prototype.now = $utils.formatDate(new Date)

Vue.component('customerService', $customerService)
Vue.component('settingItem', $settingItem)
Vue.component('btnCollections', $btnCollections)

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
