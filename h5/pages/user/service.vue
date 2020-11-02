<template>
	<view>
		<view class="serivce-content">
			<view class="dot-wrap">
				<view class="dot-item" style="width: 60rpx; height: 60rpx" :style="{width: item.diameter+'rpx', height: item.diameter+'rpx', left: item.location[0]+'rpx', top: item.location[1]+'rpx', backgroundColor: item.backgroundColor}"  v-for="(item, index) in dotList" :key="index"></view>
			</view>
			<view class="service-qrcode-wrap">
				<image class="service-qrcode" :src="data.qrcodeImg"></image>
				<view class="service-qrcode-hint">长按识别客服二维码</view>
			</view>
			<view class="service-tel-wrap">
				<text>客服电话：</text>
				<text>{{ data.tel }}</text>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				data: {
					qrcodeImg: '../../static/commodity1.png',
					tel: '010-88888888'
				},
				dotList: [{
					diameter: 10,
					location: [100, 200],
					backgroundColor: '#249344'
				},{
					diameter: 20,
					location: [10, 200],
					backgroundColor: '#249344'
				},{
					diameter: 30,
					location: [100, 0],
					backgroundColor: '#F6DC49'
				},{
					diameter: 30,
					location: [200, 500],
					backgroundColor: '#F6DC49'
				},{
					diameter: 50,
					location: [600, 200],
					backgroundColor: '#F6DC49'
				},{
					diameter: 90,
					location: [500, 100],
					backgroundColor: '#249344'
				},{
					diameter: 90,
					location: [220, -30],
					backgroundColor: '#249344'
				},{
					diameter: 80,
					location: [300, 560],
					backgroundColor: '#249344'
				},{
					diameter: 60,
					location: [130, 390],
					backgroundColor: '#249344'
				},{
					diameter: 90,
					location: [700, 300],
					backgroundColor: '#F6DC49'
				},{
					diameter: 90,
					location: [120, 200],
					backgroundColor: '#F6DC49'
				},{
					diameter: 38,
					location: [600, 560],
					backgroundColor: '#249344'
				},{
					diameter: 60,
					location: [660, 20],
					backgroundColor: '#249344'
				},{
					diameter: 10,
					location: [500, 30],
					backgroundColor: '#249344'
				},{
					diameter: 20,
					location: [390, 60],
					backgroundColor: '#F6DC49'
				},{
					diameter: 20,
					location: [350, 150],
					backgroundColor: '#249344'
				},{
					diameter: 100,
					location: [-50, 300],
					backgroundColor: '#249344'
				},{
					diameter: 20,
					location: [68, 600],
					backgroundColor: '#249344'
				}]
			};
		}
	}
</script>

<style lang="scss" scoped>
	.serivce-content{
		.dot-wrap{
			min-height: 100vh;
			width: 750rpx;
			position: relative;
			.dot-item{
				position: absolute;
				border-radius: 50%;
				background-color: #eee;
				// width: 50rpx;
				// height: 50rpx;
			}
		}
		.service-qrcode-wrap{
			text-align: center;
			position: absolute;
			top: 39%;
			left: 50%;
			transform: translate(-50%, -50%);
			.service-qrcode{
				width: 300rpx;
				height: 300rpx;
			}
			.service-qrcode-hint{
				color: #666;
			}
		}
		.service-tel-wrap{
			position: absolute;
			width: 750rpx;
			bottom: 100rpx;
			text-align: center;
			text {
				color: $uni-color-primary;
				font-size: 32rpx;
			}
		}
	}
</style>
