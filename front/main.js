import Vue from 'vue'
import App from './App'

import pageHead from './components/page-head.vue'
import pageFoot from './components/page-foot.vue'
import uLink from '@/components/uLink.vue'
import store from './store'
import { http } from '@/common/service.js' // 全局挂载引入，配置相关在该index.js文件里修改
/***
import MescrollBody from "@/components/mescroll-uni/mescroll-body.vue"
import MescrollUni from "@/components/mescroll-uni/mescroll-uni.vue"
Vue.component('mescroll-body', MescrollBody)
Vue.component('mescroll-uni', MescrollUni)
**/
Vue.prototype.$http = http
Vue.config.productionTip = false

Vue.prototype.$store = store
Vue.prototype.$backgroundAudioData = {
	playing: false,
	playTime: 0,
	formatedPlayTime: '00:00:00'
}
let url = ""
Vue.prototype.$adpid = "1111111111" 
Vue.prototype.wechatAppId = ""
Vue.prototype.templateId = ""
Vue.prototype.shareText = "点外卖，先领券！美团饿了么都有！"
Vue.prototype.shareImage = url  + "/images/share.png"
Vue.prototype.notifyImage = url  + "/images/notify.png"
Vue.prototype.contactImage = url  + "/images/contact.png"
Vue.prototype.raiseImage = url  +"/images/raise.png"

Vue.component('page-head', pageHead)
Vue.component('page-foot', pageFoot)
Vue.component('uLink', uLink)

App.mpType = 'app'

const app = new Vue({
	store,
	...App
})
app.$mount()
