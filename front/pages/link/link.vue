<template>
	<view>
		<view class="link-container" v-if="total > 0">
			<view class="link-item" v-for="item, index in links" :key="item.id" @tap="goToLink(index)">
				<view class="logo">
					<image  :src="item.logo"></image>
				</view>
				<view class="link-middle">
					<view class="title">
						 {{item.app_name}}
					</view>
					<view class="desciption">
						{{item.description}}
					</view>  
				</view>
				<view class="go-link">
					<image src="../../static/to.png"></image>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				links: [],
				total: 0,
			}
		},
		onLoad() {
			this.loadLinks()
		},
		methods: {
			goToLink:function(e){
				let link = this.links[e]
				console.log(link)
				uni.navigateToMiniProgram({
				  appId: link.app_id,
				  path: link.url,
				  extraData: {
				  },
				  success(res) {
				    // 打开成功
				  }
				})
			},
			loadLinks: function(){
				let url = '/link?app_id=' + this.wechatAppId
				this.$http.get(url).then(res => {
					//console.log(res) 
					let links = res.data.data
					console.log(links)
					if (links) {
						this.links = links
					}
					this.total = links.length
				})
			}
		}
	}
</script>

<style>
	page{
		background-color: #f1f1f1 !important
	}
	.link-container{
		margin: 50rpx 30rpx; 
	}
	.link-item{
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		height: 100rpx;
		padding: 30rpx;
		margin:20rpx auto;
		background-color:#ffffff;
		border-radius: 10upx;
		box-shadow: 0 5upx 20upx 0upx rgba(0, 0, 150, .2);
	}	
	.logo image{
		border-radius: 50%;
		width: 100rpx;
		height: 100rpx;
		box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);
	}
	.link-middle{
		width: 70%;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		margin: 0 20rpx;
	}
	.title {
		vertical-align: text-top;
		font-size: 32rpx;
		font-weight: 500;
	}	
	.desciption{
		font-size: 26rpx;
	}
	.go-link{
		display: flex;
		flex-direction: column;
		justify-items:center;
		justify-content: center;
	}
	.go-link image {
		height: 50rpx;
		width: 50rpx;
	}

</style>
