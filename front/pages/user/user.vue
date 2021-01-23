<template>
	<view>
		<view class="head">
			<view class="head-picture">	<open-data type="userAvatarUrl"></open-data></view>
			<view class="nickname"><open-data type="userNickName"></open-data> </view>
		</view>
		<view class="content">
			<uni-list>
				<uni-list-item clickable v-for="item, index in navList" :key="item.id" @click="goToLink(index)" :show-extra-icon="true" :extra-icon="item.icon" :title="item.title"/>
			</uni-list>
		</view>
	</view>
</template>
<style>
	.head{
		background-color: #FFFFFF;
		padding: 40upx;
		margin-bottom: 30upx;
		display: flex;
		flex-direction: row;
		align-items: center
	}
	.head-picture{
		width: 100upx;
		height: 100upx;
		background: #fff;
		border: 5upx solid #fff;
		border-radius: 50%;
		overflow: hidden;
	}
	.nickname{
		margin-left: 30upx;
		font-size: 36upx;
		font-weight: 500;
		text-align: left;
	}
</style>
<script>
	import uniIcons from '@/components/uni-icons/uni-icons.vue'
	import uniSection from '@/components/uni-section/uni-section.vue'
	import uniList from '@/components/uni-list/uni-list.vue'
	import uniListItem from '@/components/uni-list-item/uni-list-item.vue'
	export default {
		components: {
			uniSection,
			uniList,
			uniListItem,
			uniIcons
		},
		data() {
			return {
				navList: [ 
						{
							icon :{
									color: '#f4c242',
									size: '28',
									type: 'gear'
								},
							title: "领券提醒",
							type : 3,
							url: '',
						}
				]		
			}
		},onShareAppMessage(res) {
			if (res.from === 'button') {// 来自页面内分享按钮
			  console.log(res.target)
			}
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
			notify: function(){
					let http = this.$http
					console.log(http)
					let templateId = this.templateId
					uni.requestSubscribeMessage({
					  tmplIds: [templateId],
					  success (res) {
						 console.log(res)
						 if (res[templateId] == 'reject') {
							 uni.showToast({
								icon: 'none',
								title: '您拒绝了我明天就没提醒了哦',
								duration: 3000
							  });
							 return;
						 }
						 if (res[templateId] == 'ban') {
							 uni.showToast({
								icon: 'none',
								title: '请移步到设置打开订阅功能',
								duration: 3000
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
									icon: 'none',
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
				},
			goToLink: function(index) {
				let item = this.navList[index]
				let type = item.type
				let url = item.url
				console.log(item)
				if (type == 2) { 
					uni.navigateTo({
						url: url
					});
				} else if (type == 3) {
					this.notify()   //领券提醒
				} else {
					console.log(url)
					  uni.previewImage({
					            urls: [url],
					            longPressActions: {
					                itemList: ['发送给朋友', '保存图片', '收藏'],
					                success: function(data) {
					                    console.log('选中了第' + (data.tapIndex + 1) + '个按钮,第' + (data.index + 1) + '张图片');
					                },
					                fail: function(err) {
					                    console.log(err.errMsg);
					                }
					            }
					 });
				}
				
			}
			
		}
	}
</script>

<style>

</style>
