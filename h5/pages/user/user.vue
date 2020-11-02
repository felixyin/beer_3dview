<template>
	<view class="user-wrap">
		<!-- 用户信息 -->
		<view class="user-info">
			<image class="user-info-bg" src="../../static/user-bg.jpg" mode="aspectFill"></image>
			<view class="user-info-bg-mask">
				<image class="user-info-logo" :src="userImg" mode="aspectFill"></image>
				<view class="use-info-nickname">{{ userName }}</view>
				<view class="user-info-btn" @tap="togo('themeList')">开启定制</view>
			</view>
		</view>
		<!-- 订单信息 -->
		<view class="menu-list-item menu-list-horizon order">
			<view class="menu-item" v-for="(item, index) in menuList.order" :key="item.id" @tap="togo(item.routePath)">
				<view class="menu-item-title">{{ item.title }}</view>
				<view class="menu-item-banner">
					<text class="menu-item-banner-text">{{ item.banner }}</text>
					<text class="cuIcon-right"></text>
				</view>
			</view>
		</view>
		<!-- 订单展开 -->
		<view class="menu-list-item menu-list-vertical">
			<view class="menu-item-vertical" v-for="(item, index) in menuList.orderUnfold" :key="item.id" @tap="togo(item.routePath)">
				<view class="menu-item-vertical-icon" :class="item.icon"></view>
				<view class="menu-item-vertical-title">{{ item.title }}</view>
			</view>
		</view>
		<!-- 优惠券 -->
		<view class="menu-list-item menu-list-horizon coupon">
			<view class="menu-item" v-for="(item, index) in menuList.coupon" :key="item.id" @tap="togo(item.routePath)">
				<view class="menu-item-title">{{ item.title }}</view>
				<view class="menu-item-banner">
					<text class="menu-item-banner-text">{{ item.banner }}</text>
					<text class="cuIcon-right"></text>
				</view>
			</view>
		</view>
		<!-- 其他菜单 -->
		<view class="menu-list-item menu-list-horizon other-menu">
			<view class="menu-item" v-for="(item, index) in menuList.otherMenu" :key="item.id" @tap="togo(item.routePath)">
				<view class="menu-item-title">{{ item.title }}</view>
				<view class="menu-item-banner">
					<text class="menu-item-banner-text">{{ item.banner }}</text>
					<text class="cuIcon-right"></text>
				</view>
			</view>
		</view>
		<!-- 退出登录 -->
		<view class="quit" @tap="logout">退出登录</view>
		<customerService></customerService>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				userImg: '../../static/logo.png',
				userName: 'ls',
				// 菜单列表
				menuList: {
					// 订单
					order: [{
						id: 0,
						title: '我的订单',
						routePath: '',
						banner: '查看更多订单'
					},{
						id: 1,
						title: '售后订单',
						routePath: '',
						banner: '查看售后订单'
					}],
					// 订单 - 展开
					orderUnfold: [{
						id: 0,
						title: '待付款',
						routePath: '',
						icon: 'cuIcon-pay'
					},{
						id: 1,
						title: '待确认',
						routePath: '',
						icon: 'cuIcon-text'
					},{
						id: 2,
						title: '待发货',
						routePath: '',
						icon: 'cuIcon-send'
					},{
						id: 3,
						title: '已发货',
						routePath: '',
						icon: 'cuIcon-deliver'
					},{
						id: 4,
						title: '已完成',
						routePath: '',
						icon: 'cuIcon-goodsnew'
					}],
					// 优惠券
					coupon: [{
						id: 0,
						title: '我的优惠券',
						routePath: '',
						banner: ''
					},{
						id: 1,
						title: '领券中心',
						routePath: '',
						banner: ''
					}],
					// 其他菜单
					otherMenu: [{
						id: 0,
						title: '发票信息',
						routePath: '',
						banner: ''
					},{
						id: 1,
						title: '收货地址',
						routePath: '',
						banner: ''
					},{
						id: 2,
						title: '个人资料',
						routePath: 'userinfo',
						banner: ''
					},{
						id: 3,
						title: '联系我们',
						routePath: 'service',
						banner: ''
					},{
						id: 4,
						title: '企业信息',
						routePath: '',
						banner: ''
					}]
				}
			};
		},
		methods: {
			/**
			 * @description 页内路由
			 * @param {String}  = [pathName] route.index.js 页面对应的键名
			 * @param {Object}  = [data] 路由跳转时携带的数据 
			 */
			
			togo: function(pathName, data={}){
				console.log(pathName)
				if(!pathName) return
				this.commonJs.togo(pathName, data)
			},
			
			/**
			 * @description 退出登录
			 */
			logout: function(){
				console.log('[+] LOGOUT')
			}
		}
	}
</script>

<style lang="scss" scoped>
	.user-wrap{
		background-color: $color-grey-light-double;
		min-height: 100vh;
		overflow: hidden;
		position: relative;
	}
	.user-info{
		height: 500rpx;
		overflow: hidden;
		position: relative;
		.user-info-bg{
			width: 750rpx;
			height: 500rpx;
		}
		.user-info-bg-mask{
			background-color: rgba(0, 0, 0, .5);
			position: absolute;
			left: 0;
			top: 0;
			width: 750rpx;
			height: 500rpx;
		}
		.user-info-logo{
			width: 126rpx;
			height: 126rpx;
			border-radius: 50%;
			position: absolute;
			left: 50%;
			margin-left: -62rpx;
			top: 100rpx;
		}
		.use-info-nickname{
			color: #fff;
			position: absolute;
			left: 50%;
			transform: translateX(-50%);
			top: 252rpx;
		}
		.user-info-btn{
			position: absolute;
			left: 50%;
			width: 260rpx;
			height: 68rpx;
			background-color: $uni-color-primary;
			color: #fff;
			text-align: center;
			border-radius: 50rpx;
			line-height: 68rpx;
			margin-left: -130rpx;
			bottom: 100rpx;
		}
	}
	
	.menu-list-item{
		background-color: #fff;
		margin-bottom: 20rpx;
		&.menu-list-horizon{
			padding-left: 20rpx;
		}
		.menu-item{
			display: flex;
			justify-content: space-between;
			align-items: center;
			height: 90rpx;
			padding-right: 20rpx;
			&:not(:last-child){
				border-bottom: 2rpx solid $color-grey-light-double;
			}
			* {
				font-size: 30rpx;
			}
			.menu-item-title{
				
			}
			.menu-item-banner{
				display: flex;
				align-items: center;
				.menu-item-banner-text{
					font-size: 26rpx;
					position: relative;
					top: -2rpx;
					margin: 10rpx;
					color: $color-grey-dark;
				}
				.cuIcon-right{
					color: $color-grey;
				}
			}
		}
		&.menu-list-vertical{
			display: flex;
			align-items: center;
			.menu-item-vertical{
				width: 150rpx;
				text-align: center;
				height: 150rpx;
				padding: 25rpx 0;
				.menu-item-vertical-icon{
					font-size: 56rpx;
				}
				.menu-item-vertical-title{
					
				}
			}
		}
	}
	
	.quit{
		background-color: #fff;
		height: 90rpx;
		line-height: 90rpx;
		text-align: center;
		margin-bottom: 100rpx;
	}
</style>
