<template>
	<view class="theme-list-wrap">
		<view class="theme-list-item" v-for="(item, index) in data" :key="item.id" @tap="togo('commodityList', item.id)">
			<image class="item-img" :src="item.thumbnail" mode="widthFix"></image>
			<view class="item-text">{{ item.name }}</view>
		</view>
	</view>
</template>

<script>
	/**
	 * @description 主体列表
	 * @info themeList接口每页数据由10条
	 */
	export default {
		onShow() {
			this.getData()
		},
		onReachBottom(){
			this.getData()
		},
		data() {
			return {
				currentPage: 0,						// 当前页码
				lastPage: 1,						// 最后一份数据页码
				data: ''							// 数据存储
			};
		},
		methods: {
			/**
			 * @description 获取数据
			 * 
			 */
			getData: function(){
				let _this = this
				if(this.currentPage < this.lastPage){
					this.commonJs.getData('themeList', {
						page: this.currentPage+1,
						search: ''
					}).then(res => {
						_this.lastPage = res.data.cat.last_page
						_this.currentPage = res.data.cat.current_page
						_this.data = res.data.cat.data
					})
				}else{
					// 没有数据了
					console.log('there is no data.')
				}
			},
			
			/**
			 * @description 页内路由
			 * @param {String}  = [pathName] route.index.js 页面对应的键名
			 * @param {Object}  = [data] 路由跳转时携带的数据 
			 */
			
			togo: function(pathName, data={}){
				this.commonJs.togo(pathName, data)
			}
		}
	}
</script>

<style lang="scss" scoped>
	.theme-list-wrap{
		padding: 20rpx;
	}
	.theme-list-item{
		margin-bottom: 30rpx;
		.item-img{
			width: 710rpx;
			height: 200rpx;
			// background-color: #eee;
			border-radius: 20rpx;
		}
		.item-text{
			margin-left: 6rpx;
			font-size: 26rpx;
		}
	}
</style>
