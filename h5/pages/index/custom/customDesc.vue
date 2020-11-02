<template>
	<view class="costom-desc-wrap">
		<view class="commodity-info">
			<view class="commodity-main">
				<image class="commodity-img" :src="commodityName.img"></image>
				<view class="commodity-main-content">
					<view class="commodity-name">{{ commodityName.title }}</view>
					<view class="commodity-purple commodity-price">￥{{ commodityName.price }}</view>
					<view class="commodity-purple commodity-condition">{{ commodityName.condition }}</view>
				</view>
			</view>
			<view class="commodity-info-item" v-for="(item, index) in commodityDetail" :key="index">
				<view class="info-item-title">{{ item.title }}</view>
				<view class="info-item-data" v-for="(detailItem, detailIndex) in item.data" :key="detailIndex">
					<view class="info-item-name">{{ detailIndex }}</view>
					<view class="info-item-value">{{ detailItem }}</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				commodityName: {
						img: '../../../static/styleOmit.png',
						title: '祥瑞百乐',
						price: '189/箱',
						condition: '15箱/起订'
					},
				commodityDetail: [{
						title: '产品信息',
						data: {
							'产品编号': 'ssDKF883NJkdfs',
							'原料': '水、麦芽、大米、啤酒花及制品水、麦芽、大米、啤酒花及制品水、麦芽、大米、啤酒花及制品',
							'酒精度': '4.3%vol',
							// '原麦汁浓度': '11.0',
							// '保质期': '365天',
							// '产地': '山东省青岛市'
						}
					},{
						title: '产品规格',
						data: {
							'罐长': '118mm',
							'罐宽': '65mm',
							'容量': '330ml'
						}
					}]
			};
		}
	}
</script>

<style lang="scss" scoped>
	.costom-desc-wrap{
		background-color: $color-grey-light-double;
		min-height: 100vh;
	}
	.commodity-info{
		.commodity-main{
			padding-bottom: 20rpx;
			position: relative;
			background-color: #fff;
			.commodity-img{
				position: absolute;
				left: 20rpx;
				top: 26rpx;
				width: 180rpx;
				height: 180rpx;
				border-radius: 6rpx;
				border: 2rpx solid #C8C8C8;
			}
			.commodity-main-content{
				overflow: hidden;
				.commodity-name{
					font-size: 30rpx;
					padding-left: 220rpx;
					margin: 50rpx 0 20rpx 0;
				}
				.commodity-purple{
					padding: 6rpx 0;
					padding-left: 220rpx;
					background-color: #EEF2FE;
					&.commodity-price{
						color: $uni-color-primary;
					}
					&.commodity-condition{
						color: $uni-color-primary;
						margin-top: 10rpx;
					}
				}
			}
		}
		.commodity-info-item{
			margin-top: 20rpx;
			background-color: #fff;
			padding-left: 20rpx;
			.info-item-title{
				height: 80rpx;
				line-height: 80rpx;
				color: $uni-color-primary;
				border-bottom: 2rpx solid $color-grey-light-double;
			}
			.info-item-data{
				min-height: 68rpx;
				line-height: 68rpx;
				display: flex;
				justify-content: space-between;
				align-items: center;
				padding: 12rpx 0;
				padding-right: 20rpx;
				&:not(:last-child){
					border-bottom: 2rpx solid $color-grey-light-double;
				}
				.info-item-name{
					
				}
				.info-item-value{
					width: 500rpx;
					text-align: right;
					line-height: 1.6;
				}
			}
		}
	}
</style>
