<template>
	<view>
		<!--  #ifdef  MP-WEIXIN -->
		
		<show-tips></show-tips>
		<view class="notify">
			<image @tap="notify" mode="widthFix" :src="this.notifyImage ? this.notifyImage : '../../static/notify.png'"></image>
		</view>
		<!--  #endif -->
		<view class="project-container">
			<view class="project_item" v-for="item, index in projects" :key="item.id">
			    <view class="project-header">
					<view class="title">{{item.title}}</view><button class="share" open-type="share"  :data-index="index"><image mode="widthFix"  src="../../static/share.png"></image></button>
				</view>
				<view class="project-content" @tap="goToProject(index)">
					<view  v-if="item.image" class="image" @tap="previewImage(index)"><image mode="widthFix" :src="item.image"></image></view>
					<view v-if="item.description" class="description">{{item.description}}</view>
				</view>
			</view>
		</view>
		<!--  #ifdef H5 -->
		
		<view style="text-align: center;">
			<h3>关注微信公众号<text style="color:red;">吃喝优惠券</text>获取更多优惠信息</h3>
			<image style="margin-top: 20upx;" src="https://lajun-chihe.oss-cn-beijing.aliyuncs.com/chihe-gongzhonghao.jpg"></image>
		</view>
		<!--  #endif -->
	</view>
</template>

<script>
	import showTips from '../../wxcomponents/redflower-showTips/redflower-showTips.vue'

	export default {
		 components: {
			 showTips
		 },
		onLoad() {
			this.getProjects()
		},
		data() {
			return {
				projects: []
			}
		},
	    onShareAppMessage(res) {
			return {
			  title: this.shareText,
			  path: '/pages/index/index',
			  imageUrl: this.shareImage
			}
			  
		},
		
		onShareTimeline: function() {
			return {
			  title: this.shareText,
			  query: 'where=share',
			  imageUrl: this.shareImage
			}
		},

		methods: {
			getProjects:function() {
				let url = '/project'
				// #ifdef H5
				url = url + '?platform=h5'
				// #endif
				this.$http.get(url,{}).then(res => {
						let data = res.data
						if (data.code == 1) {
							this.projects =  data.data;
							console.log(this.projects)
						} else {
							uni.hideLoading()
							uni.showToast({
								icon: 'none',
								title: data.msg,
								duration: 1000
							});
						}
					}).catch(function(){
						uni.hideLoading()
					})
			},
			goToProject: function(index) {
				let project = this.projects[index]
				uni.navigateToMiniProgram({
				  appId: project.app_id, 
				  path: project.path,
				  success(res) {
					 // 打开成功
					 console.log(res + 12312)
					 let params = {
					 	 project_id : project.id,
					 	 token: token 
					 }
					 console.log(params)
					 http.post('/add-coupon-log',params).then(res => {
					 		let data = res.data
					 	    console.log(data)
					 }).catch(function(e){
					 	console.log(e)
					 })
				  }
				})
			},
			shareProject: function(index) {
				let project = this.projects[index]
				uni.navigateToMiniProgram({
				  appId: project.app_id, 
				  path: project.path,
				  success(res) {
					
				  }
				}) 
			}, 
			previewImage: function(index) {
				
			},
			notify: function(){
				let http = this.$http
				console.log(http)
				let templateId = this.templateId
				console.log(templateId)
				uni.requestSubscribeMessage({
				  tmplIds: [templateId],
				  success (res) {
					 console.log(res)
					 if (res[templateId] == 'reject') {
						 uni.showToast({
						  	icon: 'none',
						  	title: '您拒绝了我明天就没提醒了哦',
						  	duration: 2000
						  });
						 return;
					 }
					 if (res[templateId] == 'ban') {
						 uni.showToast({
						 	icon: 'none',
						 	title: '请移步到设置打开订阅功能',
						 	duration: 2000
						 });
					    return;
					 } 
					 let token    = uni.getStorageSync('token')
					 let params = {
						 template_id : templateId,
						 token: token
					 }
					 console.log(params)
					 http.post('/add-subscribe',params).then(res => {
					 		let data = res.data
							uni.showToast({
								icon: 'success',
								title: data.msg,
								duration: 1000
							});
					 		
					 }).catch(function(e){
					 	console.log(e)
					 })
				  },
				  fail (res) {
				  	console.log(res)			  
				  }
				})
			}
		}
	}
</script>

<style> 
  .notify image{
	  width: 100%;
  }
  .project-container {
	  width: 98%;
	  margin: 30upx auto; 
  }
  .project_item{
	  margin: 20upx;
	  justify-content: space-between;
	  background-color: #fff;
	  padding: 35upx 20upx 10upx 20upx;
	  border-radius: 5upx;
	  box-shadow: 2upx 2upx 6upx #b9b9b9;
	  padding-bottom: 30upx;
  }
  .project-header{
	  display: flex;
	  justify-content: space-between;
	  flex-direction: row;
	  margin-bottom: 20upx;
  }
  .title {
	  border-left: #F0AD4E solid 8upx;
	  padding-left: 15upx;
	  line-height: 1.5rem;
	  font-size: 30upx;
	  font-weight: 500;
	  width: 80%;
	  text-align: left;
  }
  .share{
	  width: 42upx;
	  text-align: right;
	  background-color: white;
	  padding: 0;
	  line-height: 1;
	  border: 0;
	  text-align: right;
  }
  .share::after{
  	border: 0;
  	width: 0;
	height: 0;
  }
  .share image{
	  width: 100%;
  }
  .image{
	  width: 100%; 
  }
  .image image{
	  width: 100%;
  }
  .description{
	  font-size: 28upx;
	  font-weight: 350;
  }
</style>
