<template>
	<view>
		<view 
			class="btn-colloctions"
			:class="{'btn-primary': type == 'primary'}"
		>
			<slot />
		</view>
	</view>
</template>

<script>
	/**
	 * @description 按键样式
	 * @param {String} type = ['primary'|'white'|'grey'] 按键样式套餐
	 * 							primary	绿底白字（默认样式）
	 * 							white 	白底绿字
	 * 							grey	白底灰字
	 */
	export default {
		name: 'btnCollections',
		props: {
			type: {
				type: String,
				default: 'primary'
			}
		},
		data() {
			return {
				
			};
		}
	}
</script>

<style lang="scss" scoped>
	.btn-colloctions{
		border-radius: 50rpx;
		margin: 0 auto;
		width: 260rpx;
		height: 90rpx;
		line-height: 90rpx;
		text-align: center;
		font-size: 32rpx;
		&.btn-primary{
			color: #fff;
			background-color: $uni-color-primary;
			border: 2rpx solid $uni-color-primary;
		}
		&.btn-white{
			color: $uni-color-primary;
			border: 2rpx solid $uni-color-primary;
		}
		&.btn-grey{
			color: $color-grey-dark;
			border: 2rpx solid $color-grey;
		}
	}
</style>
