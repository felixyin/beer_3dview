<template>
	<view>
		<view class="customer-service" @tap="openPanel('im')">
			<text class="cuIcon-messagefill"></text>
		</view>
		<uni-popup ref="seviceDialog" type="bottom">
			<view class="sevice-dialog">
				<view class="sevice-dialog-top">
					<view class="cuIcon-close" @tap="closePanel('im')"></view>
					<view class="sevice-dialog-title">私人定制</view>
				</view>
				<!-- 对话内容 -->
				<view class="">
					
				</view>
				<!-- 输入框 -->
				<view class="">
					
				</view>
			</view>
		</uni-popup>
		<uni-popup ref="leaveNote" type="bottom">
			<view class="leave-note">
				<!-- 顶部 -->
				<view class="leave-note-top">
					<view class="leave-note-title">填写留言</view>
					<view class="leave-note-close cuIcon-close" @tap="closePanel('note')"></view>
				</view>
				<!-- 内容 -->
				<view class="leave-note-content">
					<view class="leave-note-hint">
						当前客服不在线，如需帮助请留言，请留下您的联系方式和遇到的问题，我们将尽快联系您。
					</view>
					<!-- 联系方式 -->
					<view class="leave-note-contact">
						<view class="leave-note-contact-hint">
							<text>手机号或者微信号</text>
							<text class="important">*</text>
						</view>
						<input class="leave-note-contact-input" v-model="noteContact" type="text" placeholder="请输入手机或微信号" />
					</view>
					<!-- 留言内容 -->
					<view class="leave-note-input-wrap">
						<view class="leave-note-input-hint">
							<text>留言</text>
							<text class="important">*</text>
						</view>
						<textarea class="leave-note-input" v-model="noteContent" placeholder="请输入留言" />
					</view>
					<!-- 发送 -->
					<view class="send-btn send-btn-check" v-if="noteContact && noteContent" @tap="sendBtn">发送</view>
					<view class="send-btn" v-else @tap="sendBtn">发送</view>
				</view>
			</view>
		</uni-popup>
	</view>
</template>

<script>
	/**
	 * @description 客户服务IM
	 * 
	 */
	import UniPopup from './uni-popup/uni-popup.vue'
	export default {
		name: 'customerService',
		components: {
			UniPopup
		},
		mounted() {
			// this.openPanel('note')
		},
		data() {
			return {
				noteContact: '',						// 留言手机号或者微信号
				noteContent: '',						// 留言内容
			};
		},
		methods: {
			/**
			 * @description 弹出面板
			 * @param {String} type = ['im'|'note'] 弹出面板的类型 'im' 即时通讯 'note' 用户留言
			 */
			openPanel: function(type){
				if(type == 'im') {
					this.$refs.seviceDialog.open()
					// 判断是否开启 留言板 面板 TODO
					if(1){
						this.$refs.leaveNote.open()
					}
				}
				if(type == 'note') this.$refs.leaveNote.open()
			},
			
			/**
			 * @description 关闭面板
			 * @param {String} type = ['im'] 关闭面板的类型 'im' 即时通讯 'note' 用户留言
			 */
			
			closePanel: function(type){
				if(type == 'im'){
					this.$refs.seviceDialog.close()
				}else if(type == 'note'){
					this.$refs.leaveNote.close()
				}
			},			
			
			/**
			 * @description 发送数据
			 * 
			 */
			
			sendBtn: function(){
				// 发送数据 TODO
			}
		}
	}
</script>

<style lang="scss" scoped>
	.customer-service{
		height: 130rpx;
		width: 130rpx;
		line-height: 130rpx;
		text-align: center;
		border-radius: 20rpx;
		background-color: #F3A553;
		position: fixed;
		right: 36rpx;
		bottom: 50rpx;
		box-shadow: 0 6rpx 30rpx 0 #999;
		.cuIcon-messagefill{
			color: #fff;
			font-size: 90rpx;
		}
	}
	.sevice-dialog{
		width: 750rpx;
		height: 1126rpx;
		background-color: #fff;
		.sevice-dialog-top{
			background-color: $color-green-dialog;
			text-align: center;
			height: 80rpx;
			line-height: 80rpx;
			.cuIcon-close{
				color: #fff;
				position: absolute;
				left: 30rpx;
				font-size: 36rpx;
			}
			.sevice-dialog-title{
				color: #fff;
				font-size: 32rpx;
			}
		}
	}
	
	.leave-note{
		height: 900rpx;
		width: 750rpx;
		background-color: #F2F3F5;
		border-radius: 10rpx 10rpx 0 0;
		.leave-note-top{
			background-color: #fff;
			height: 90rpx;
			display: flex;
			justify-content: space-between;
			padding: 0 20rpx;
			line-height: 90rpx;
			border-radius: 10rpx 10rpx 0 0;
			.leave-note-title{
				font-size: 35rpx;
			}
			.leave-note-close{
				font-size: 32rpx;
			}
		}
		.leave-note-content{
			padding: 50rpx;
			* {
				font-size: 30rpx;
			}
			.leave-note-hint{
				margin-bottom: 50rpx;
			}
			.leave-note-contact,
			.leave-note-input-wrap{
				.leave-note-contact-hint,
				.leave-note-input-hint{
					.important{
						color: #5793DC;
						margin-left: 10rpx;
					}
				}
				.leave-note-contact-input,
				.leave-note-input{
					width: 660rpx;
					border: 2rpx solid $color-grey;
					height: 80rpx;
					border-radius: 6rpx;
					padding-left: 20rpx;
					margin-top: 16rpx;
					.input-placeholder{
						color: $color-grey;
					}
				}
			}
			.leave-note-input-wrap{
				margin-top: 60rpx;
				.leave-note-input-hint{
					.important{
						color: #5793DC;
						margin-left: 10rpx;
					}
				}
				.leave-note-input{
					min-height: 200rpx;
					padding: 20rpx;
					.textarea-placeholder{
						color: $color-grey;
					}
				}
			}
			.send-btn{
				background-color: $color-green;
				color: #fff;
				width: 160rpx;
				height: 68rpx;
				border-radius: 10rpx;
				text-align: center;
				line-height: 68rpx;
				margin: 50rpx 50rpx 0 auto;
				&.send-btn-check{
					background-color: $uni-color-primary;
				}
			}
		}
	}
</style>
