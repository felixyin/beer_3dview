<template>
	<view class="demo-show">
		<view class="cube-show">
			
		</view>
		<view class="btn-wrap">
			<view class="btn btn-white">返回</view>
			<view class="btn btn-green">确认定制</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			};
		}
	}
</script>

<style lang="scss" scoped>
	.demo-show{
		background-color: #F7F7F7;
		min-height: 100vh;
		position: relative;
	}
	.cube-show{
		
	}
	.btn-wrap{
		position: absolute;
		bottom: 100rpx;
		left: 50%;
		margin-left: -130rpx;
		.btn{
			width: 260rpx;
			height: 68rpx;
			line-height: 68rpx;
			border-radius: 50rpx;
			text-align: center;
			font-size: 30rpx;
			&.btn-white{
				background-color: #fff;
				margin-top: 30rpx;
			}
			&.btn-green{
				color: #fff;
				background-color: $uni-color-primary;
				margin-top: 30rpx;
			}
		}
	}
</style>
