<template>
	<view class="setting-item-wrap">
		
		<!-- 展示数据 item -->
		
		<view class="show-data normal" v-if="data.type == 'show'">
			<view class="normal-hint">{{ data.title }}</view>
			<view class="normal-show normal-content nowrap">{{ data.content }}</view>
		</view>
		
		<!-- 输入项 -->
		
		<view class="input-data normal" v-if="data.type == 'input'">
			<view class="normal-hint">{{ data.title }}</view>
			<input class="normal-input normal-content nowrap" type="text" :placeholder="data.placeholder" />
		</view>
		
		<!-- 单项选择 -->
		
		<view class="selectSingle-data normal" v-if="data.type == 'selectSingle'">
			<view class="normal-hint">{{ data.title }}</view>
			<picker class="normal-picker normal-content nowrap" @change="bindPickerChangeSelectSingle" mode="selector" :value="selectSingleIndex" :range="data.data" >
				<view class="normal-picker-text">{{ selectSingleChanged ? data.data[selectSingleIndex] : data.banner }}</view>
			</picker>
			<view class="cuIcon-right"></view>
		</view>
		
		<!-- 多项选择 -->
		
		<!-- 日期选择 -->
		
		<view class="selectDate-data normal" v-if="data.type == 'selectDate'">
			<view class="normal-hint">{{ data.title }}</view>
			<picker class="normal-picker normal-content nowrap" @change="bindPickerChangeDate($event, data)" mode="date" :value="data.banner" :start="data.startDate" :end="data.endDate" >
				<view class="normal-picker-text">{{ data.banner }}</view>
			</picker>
			<view class="cuIcon-right"></view>
		</view>
		
		<!-- 时间选择 -->
		
		<!-- 日期时间选择 -->
		
		<!-- 地区选择 -->
		
		<!-- 选中圆框 -->
		
		<!-- 上传照片 -->
	</view>
</template>

<script>
	/**
	 * @description 设置页面的输入项
	 * 
	 * @param {String} type = [value] 输入项的类型 
	 * 							show 仅展示  
	 * 							input 输入项
	 * 							selectSingle 单项选择 
	 * 							selectMulti 多项选择
	 * 							selectDate 日期选择
	 * 							selectTime 时间选择
	 * 							selectDateTime 日期时间选择
	 * 							selectRegion 地区选择
	 * 							radio 选中圆框
	 * 							uploadImg 上传照片
	 * 
	 */
	export default {
		name: 'settingItem',
		props: {
			data: {
				type: Object,
				default: () => {}
			}
		},
		data() {
			// const currentDate = this.getDate({
			// 	format: true
			// })
			return {
				// 单项选择
				
				selectSingleIndex: 0,
				selectSingleChanged: false,
				
				// 多项选择
				
				// 日期选择
				
				// 时间选择
				
				// 日期时间选择
				
				// 地区选择
				
				// 选中圆框
				
				// 上传照片
			};
		},
		methods: {
			/**
			 * @description PICKER CHANGE 事件
			 * 				bindPickerChangeGender 性别
			 */
			
			bindPickerChangeSelectSingle: function(e){
				this.selectSingleChanged = true
				this.selectSingleIndex = e.target.value
			},
			bindPickerChangeDate: function(e, data){
				 this.$set(data, 'banner', e.target.value)
			}
		}
	}
</script>

<style lang="scss" scoped>
	.setting-item-wrap{
		padding-left: 20rpx;
		background-color: #fff;
		* {
			font-size: 30rpx;
		}
	}
	.normal{
		display: flex;
		justify-content: space-between;
		align-items: center;
		height: 90rpx;
		border-bottom: 2rpx solid $color-grey-light-double;
		padding-right: 20rpx;
		.normal-hint{
			flex-shrink: 0;
		}
		.normal-content{
			flex-grow: 1;
			text-align: right;
		}
		// 展示内容
		.normal-show{
			color: $color-grey-dark;
		}
		// 输入项
		.normal-input{
			width: 500rpx;
			text-align: right;
			height: 90rpx;
			.input-placeholder{
				color: $color-grey-dark;
			}
		}
		// picker - 单项选择
		.normal-picker{
			height: 90rpx;
			line-height: 90rpx;
			.normal-picker-text{
				color: $color-grey-dark-double;
			}
		}
		.cuIcon-right{
			color: $color-grey-dark;
			font-size: 38rpx;
		}
	}
</style>
