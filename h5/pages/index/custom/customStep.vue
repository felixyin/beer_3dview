<template>
	<view>
		<!-- 上半部分 -->
		<view class="result-wrap">
			<!-- 第一步 - 选择模板 -->
			<!-- 3D模型 -->
			<view class="model-show model-show-step-1" v-if="curStep === 1">
				
			</view>
			<!-- 第二步 - 上传照片 -->
			<view class="model-show model-show-step-2" v-if="curStep === 2">
				<movable-area style="height: 800rpx; width: 750rpx;" scale-area>
					<movable-view scale out-of-bounds direction="all" style="width: 200rpx; height: 300rpx;">
						<image class="img-choosed" :style="'transform: rotate('+imgRotate[imgRotateIndex]+'deg)'" :src="templateImg" mode="widthFix"></image>
					</movable-view>
				</movable-area>
			</view>
			<!-- 第三步 - 编辑内容 -->
			<view class="model-show model-show-step-3" v-if="curStep === 3">
				<movable-area style="height: 800rpx; width: 750rpx;" scale-area>
					<movable-view scale out-of-bounds direction="all" :style="`width: 1900rpx; height: 600rpx;`">
						<view v-if="textChoosed" class="text-wrap" :class="{'text-wrap-vertical': textChoosedVertical}" :style="{transform: `rotate(${textAngle}deg)`}">
							<view class="text-choosed" :style="{fontSize: textSize*2+'rpx', color: curColor}">{{ textChoosed }}</view>
							<view class="cuIcon-roundclosefill" @tap="clearInfo('text')"></view>
						</view>
					</movable-view>
				</movable-area>
			</view>
			<!-- 第四步 - 定制完成 -->
			<view class="model-show model-show-step-4" v-if="curStep === 4">
				
			</view>
			<!-- 定制步骤 -->
			<view class="custom-step" @tap="clsoeStepShowDetail">
				<view v-if="stepShowDetail">
					<view class="custom-step-item" v-for="(item, index) in customStep" :key="index">
						<view class="step-dot" :class="{'step-dot-checked': curStep == index+1}"></view>
						<view class="step-name" :class="{'step-name-checked': curStep == index+1}">{{ item }}</view>
					</view>
				</view>
				<view class="custom-step-icon" v-else>
					<view class="custom-step-icon-green"></view>
					<view class="custom-step-icon-white"></view>
				</view>
			</view>
			<!-- 产品包装说明 -->
			<view class="custom-appearence-desc" v-if="curStep === 1" @tap="togo('customDesc')">
				<text class="cuIcon-list"></text>
				<text class="desc-text">产品包装说明</text>
			</view>
		</view>
		<!-- 下半部分 -->
		<view class="control-panel">
			<!-- 第一步 - 选择模板 -->
			<view class="control-panel-step control-panel-step-1" v-if="curStep === 1">
				<!-- 路径 -->
				<view class="class-path">
					<text class="class-path-item" :class="{'cuIcon-right':index == classPath.length-1}" v-for="(item, index) in classPath" :key="index">{{ item }}</text>
				</view>
				<!-- 样式选择 -->
				<view class="style-choose">
					<view class="style-choose-item" @tap="changeStyle(item, index)" v-for="(item, index) in styleList" :key="item.id">
						<view class="item-img-wrap">
							<view class="item-title">{{ item.title }}</view>
							<view class="item-check" :class="item.check?'cuIcon-roundcheckfill':''"></view>
							<image class="item-img" :src="item.img" mode="aspectFill"></image>
						</view>
						<view class="item-hint">{{ item.hint }}</view>
					</view>
				</view>
				<!-- 下一步 -->
				<view class="next-step btn btn-green" @tap="nextStepBtn">下一步</view>
				<!-- 辅助按键 -->
				<view class="function-btn btn btn-white">求助设计师线下服务</view>
			</view>
			<!-- 第二步 - 上传照片 -->
			<view class="control-panel-step control-panel-step-2" v-if="curStep === 2">
				<!-- 上传图片 -->
				<view class="step-2-img-wrap">
					<!-- 上传的图片 -->
					<view class="step-2-img-choose" v-if="templateImg" @tap="imgUplaod">
						<image class="step-2-img-choose-checked" :src="templateImg" mode="aspectFit"></image>
						<view class="step-2-img-choose-checked-hint">更换</view>
					</view>
					<!-- 未上传图片占位符 -->
					<view class="step-2-img-choose" v-else @tap="imgUplaod">
						<view class="cuIcon-add"></view>
						<view class="img-choose-text">上传图片</view>
					</view>
					<view class="step-2-img-rotate iconfont icon-imgRotate" @tap="rotateImg"></view>
				</view>
				<!-- 缩颈线说明 -->
				<view class="relative-hint-popup" @tap="popupManger('infoShow')">
					<text class="cuIcon-info"></text>
					什么是缩颈线？
				</view>
				<!-- 提示信息 -->
				<view class="relative-hint">建议图片大小不低于1M，以免影响制作效果</view>
				<!-- 下一步 -->
				<view class="next-step btn btn-green" @tap="nextStepBtn">下一步</view>
				<!-- 辅助按键 -->
				<view class="function-btn btn btn-grey" @tap="resetStyle">重新选择模板</view>
			</view>
			<!-- 第三步 - 编辑内容 -->
			<view class="control-panel-step control-panel-step-3" v-if="curStep === 3">
				<!-- TAB -->
				<view class="step-3-tab">
					<view class="step-3-tab-item" @tap="changeTabStep2(item, index)" v-for="(item, index) in ['文字', '颜色', '排版']" :key="index">
						<view class="step-3-tab-item-text iconfont" :class="{'step-3-tab-item-text-checked':curTabIndex === index}">
							<text :class="index===0?'icon-text':index===1?'icon-textColor':index===2?'icon-typesetting':''"></text>
							{{ item }}
						</view>
					</view>
				</view>
				<!-- 文字 -->
				<view v-if="curTabIndex === 0" class="step-3-tab-1">
					<!-- 文本添加 -->
					<view class="step-3-add-text">
						<view class="step-3-add-text-input-wrap">
							<input class="step-3-add-text-input" v-model="textChoosedTemp" type="text" placeholder="添加文本" />
						</view>
						<view class="step-3-add-text-check" @tap="checkText">
							<text class="cuIcon-check"></text>
						</view>
					</view>
					<!-- 调试字体大小 -->
					<view class="adjust-text-size">
						<view class="adjust-text-left">
							<view class="adjust-text-title">字体大小</view>
							<view class="adjust-text-reset">重置</view>
						</view>
						<view class="adjust-text-right">
							<slider class="adjust-text-slider" @changing="changeTextSize" :value="textSize" block-size="20" activeColor="#e9e9e9" min="10" max="60"/>
							<view class="adjust-text-size-show">
								<view class="adjust-text-size-min">A-</view>
								<view class="adjust-text-size-now">{{ textSize }}</view>
								<view class="adjust-text-size-max">A+</view>
							</view>
						</view>
					</view>
				</view>
				<!-- 颜色 -->
				<view v-if="curTabIndex === 1" class="step-3-tab-2">
					<view class="text-color">
						<view class="text-color-hint">字体颜色</view>
						<view class="text-color-choose">
							<view 
								@tap="changeTextColor(textColorItem, textColorIndex)"
								class="text-color-choose-item" 
								:class="{'text-color-choose-item-checked':curColorIndex === textColorIndex}" 
								v-for="(textColorItem, textColorIndex) in textColor" :key="textColorItem.id" 
								:style="{backgroundColor: textColorItem.hex, border: '2rpx solid'+textColorItem.hex}"
							>
								<!-- {{ textColorItem }} -->
								<view class="white-circle"></view>
							</view>
						</view>
					</view>
				</view>
				<!-- 排版 -->
				<view v-if="curTabIndex === 2" class="step-3-tab-3">
					<!-- 文本横向 || 文本纵向-->
					<view class="step-3-text-orient">
						<view class="text-orient-item" :class="{'text-orient-item-checked':curOrientType === 0}" @tap="changeOrient(0)">
							<view class="text-orient-item-text">「 横版 」</view>
						</view>
						<view class="text-orient-item" :class="{'text-orient-item-checked':curOrientType === 1}" @tap="changeOrient(1)">
							<view class="text-orient-item-text">「 竖版 」</view>
						</view>
					</view>
					<!-- 调试字体角度 -->
					<view class="adjust-text-angle">
						<view class="adjust-text-left">
							<view class="adjust-text-title">字体旋转</view>
							<view class="adjust-text-reset">重置</view>
						</view>
						<view class="adjust-text-right">
							<slider class="adjust-text-slider" @changing="changeTextAngle" :value="textAngle"  block-size="20" activeColor="#e9e9e9" min="-180" max="180"/>
							<view class="adjust-text-angle-show">
								<view class="adjust-text-angle-min">-180</view>
								<view class="adjust-text-angle-now">{{ textAngle }}</view>
								<view class="adjust-text-angle-max">180</view>
							</view>
						</view>
					</view>
				</view>
				<!-- 缩颈线说明 -->
				<view class="relative-hint-popup" @tap="popupManger('infoShow')">
					<text class="cuIcon-info"></text>
					什么是缩颈线？
				</view>
				<!-- 下一步 -->
				<view class="next-step btn btn-green" @tap="nextStepBtn">生成</view>
				<!-- 辅助按键 -->
				<view class="function-btn btn btn-grey" @tap="resetStyle">重新选择模板</view>
			</view>
			<!-- 第四步 - 定制完成 -->
			<view class="control-panel-step control-panel-step-4" v-if="curStep === 4">
				<!-- 3D效果查看 -->
				<view class="cube-placeholder-wrap">
					<view class="cube-placeholder iconfont icon-rollCube"></view>
					<view class="cube-placeholder-text">点击查看3D立体效果图</view>
				</view>
				<!-- 确认定制 -->
				<view class="next-step btn btn-green" @tap="checkBtn">确认定制</view>
				<!-- 辅助按键 -->
				<view class="function-btn btn btn-grey" @tap="resetStyle">重新选择模板</view>
			</view>
			<!-- 客服 -->
			<customer-service></customer-service>
		</view>
		<!-- 模板重置 -->
		<uni-popup ref="resetTemplate">
			<view class="reset-template">
				<view class="reset-template-title">是否重新选择模板？</view>
				<view class="reset-template-subtitle">当前所做的编辑都将丢失</view>
				<view class="reset-template-btn">
					<view class="reset-template-btn-item reset-template-btn-cancel" @tap="resetBtn('cancel')">取消</view>
					<view class="reset-template-btn-item reset-template-btn-check" @tap="resetBtn('check')">确定</view>
				</view>
			</view>
		</uni-popup>
		<!-- 缩颈线 -->
		<uni-popup ref="infoShow" type="bottom">
			<view class="info-show">
				<view class="cuIcon-roundclosefill" @tap="popupManger('infoShow', 'close')"></view>
				<view class="info-img-show">
					<image class="info-img" src="../../../static/suojing.png" mode="widthFix"></image>
					<view class="info-img-hint">
						--缩颈线
					</view>
				</view>
				<view class="info-show-hint">
					缩颈线为分割上方弧度区与主体罐身的参考线，为保证质量，缩颈线上方请尽量避免出现人像或过于复杂的设计，以免发生变形。
				</view>
			</view>
		</uni-popup>
	</view>
</template>

<script>
	import customerService from '../../../components/customer-service.vue'
	import UniPopup from '../../../components/uni-popup/uni-popup.vue'
	export default {
		components: {
			customerService,
			UniPopup
		},
		data() {
			return {
				curStep: 1,								// 当前步骤 步骤1对应数字1
				curTabIndex: 0,							// 步骤3 当前选中的TAB页index
				curColorIndex: 0,						// 步骤3 当前选中的颜色 index
				curColor: '',							// 步骤3 当前选中的颜色
				classPath: ['企业', '全部'],				// 选择的模板路径
				textSize: '35',							// 步骤3 当前文本大小
				curOrientType: 0,						// 步骤3 当前文本排版
				textAngle: '0',							// 步骤3 当前文本角度
				templateImg: '',						// 上传的图片
				imgRotate: ['0', '90', '180', '270'],	// 图片旋转的角度
				imgRotateIndex: 0,						// 图片旋转的角度 当前index值
				textChoosed: '',						// 步骤3 用户输入的文本
				textChoosedTemp: '',					// 步骤3 用户输入的文本 用户点击 √ 之前的存储变量
				textChoosedVertical: false,					// 步骤3 用户输入的文本 排版
				stepShowDetail: true,					// 是否显示详细步骤信息
				stepShowDetailFlag: true,				// 是否显示详细步骤信息 防抖标志位
				// 模板样式
				styleList: [{
					id: 0,
					title: '年会聚会',
					hint: '深海蓝',
					check: 1,
					img: '../../../static/styleOmit.png'
				},{
					id: 1,
					title: '年会聚会',
					hint: '天空蓝',
					check: 0,
					img: '../../../static/styleOmit.png'
				},{
					id: 2,
					title: '年会聚会',
					hint: '活力蓝',
					check: 0,
					img: '../../../static/styleOmit.png'
				}],
				customStep: ['选择模板', '上传照片', '编辑内容', '定制完成'],	// 定制步骤
				textColor: [{
					id: 0,
					hex: '#FFFFFF'
				},{
					id: 1,
					hex: '#CCCCCC'
				},{
					id: 2,
					hex: '#6B6B6B'
				},{
					id: 3,
					hex: '#424242'
				},{
					id: 4,
					hex: '#FFEC0a'
				},{
					id: 5,
					hex: '#FFE0F0'
				},{
					id: 6,
					hex: '#8A71FF'
				},{
					id: 7,
					hex: '#658D6B'
				},{
					id: 8,
					hex: '#FD663B'
				},{
					id: 9,
					hex: '#C61F31'
				}]
			};
		},
		methods: {
			/**
			 * @description 修改包装样式
			 * @param {Object} obj = [value] 包装对象
			 * @param {Number} index = [value] 索引值 
			 */
			
			changeStyle: function(obj, index){
				for(let item of this.styleList){
					if(item.check) this.$set(item, 'check', false)
				}
				if(obj.check) this.$set(obj, 'check', false)
				else this.$set(obj, 'check', true)
			},
			
			/**
			 * @description 下一步
			 * 
			 */
			
			nextStepBtn: function(){
				this.curStep += 1
			},
			
			/**
			 * @description 确认定制
			 * 
			 */
			
			checkBtn: function(){
				console.log('[+] 确认定制')
			},
			resetStyle: function(){
				this.$refs.resetTemplate.open()
				// 其他需要还原的设置 TODO
			},
			resetBtn: function(type){
				if(type == 'cancel'){
					
				}else if(type == 'check'){
					this.curStep = 1
				}
				this.$refs.resetTemplate.close()
			},
			
			/**
			 * @description 图片上传
			 * 
			 */
			
			imgUplaod: function(){
				let _this = this
				uni.chooseImage({
					count: 1,
					sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
					sourceType: ['album'], //从相册选择
					success: function (res) {
						// console.log(JSON.stringify(res.tempFilePaths));
						_this.templateImg = res.tempFilePaths[0]
						_this.imgRotateIndex = 0
					}
				})
			},
			
			/**
			 * @description 修改字体大小
			 * 
			 */
			
			changeTextSize: function(e){
				this.textSize = e.detail.value
			},
			changeTextAngle: function(e){
				this.textAngle = e.detail.value
			},
			
			/**
			 * @description 步骤2 切换TAB
			 * 
			 */
			
			changeTabStep2: function(item, index){
				this.curTabIndex = index
			},
			
			/**
			 * @description 修改文本颜色
			 * 
			 */
			
			changeTextColor: function(item, index){
				this.curColorIndex = index
				this.curColor = item.hex
			},
			
			/**
			 * @description 修改当前排版
			 * @param {type}  = [0|1] 文本排版样式 0 横版 1 竖版 
			 */
			
			changeOrient: function(type){
				this.curOrientType = type
				if(type === 0){
					this.textChoosedVertical = false
				}else{
					this.textChoosedVertical = true
				}
			},
			
			/**
			 * @description 弹框管理
			 * 
			 */
			
			popupManger: function(orgin, type=''){
				if(type == 'close') this.$refs[orgin].close()
				else this.$refs[orgin].open()
			},
			
			/**
			 * @description 旋转图片角度
			 * 
			 */
			
			rotateImg: function(){
				if(this.imgRotateIndex === 3) this.imgRotateIndex = 0
				else this.imgRotateIndex += 1
			},
			
			/**
			 * @description 步骤3 用户确定输入的文本
			 * 
			 */
			
			checkText: function(){
				let _this = this
				this.textChoosed = this.textChoosedTemp
			},
			
			/**
			 * @description 关闭步骤详细信息
			 * 
			 */
			
			clsoeStepShowDetail: function(){
				if(this.stepShowDetail) clearTimeout(this.stepShowDetailFlag)
				this.stepShowDetail = true
				this.stepShowDetailFlag = setTimeout(() => {
					this.stepShowDetail = false
				}, 2000)
			},
			
			/**
			 * @description 清除 步骤2&3中 用户上传的图片或输入的文本信息
			 * @param {Object} type ['text'|'img']
			 */
			
			clearInfo: function(type){
				if(type == 'text'){
					this.textChoosed = ''
				}else if(type == 'img'){
					this.templateImg = ''
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
			
		},
		onShow() {
			this.clsoeStepShowDetail()
		}
	}
</script>

<style lang="scss" scoped>
	.result-wrap{
		height: 600rpx;
		background-color: #f8f8f8;
		position: relative;
		overflow: hidden;
		.model-show{
			position: relative;
			&.model-show-step-1{
				
			}
			&.model-show-step-2{
				position: absolute;
				// top: 200rpx;
				// left: 200rpx;
				.img-wrap{
					width: 320rpx;
					height: 160rpx;
					overflow: hidden;
					.img-choosed{
						width: 320rpx;
					}
				}
			}
			&.model-show-step-3{
				overflow: hidden;
				.text-wrap{
					position: relative;
					display: inline-block;
					.text-choosed{
						display: inline-block;
						word-break: break-all;
						line-height: 1;
						padding: 10rpx 20rpx;
						border: 2rpx solid #fff;
					}
					.cuIcon-roundclosefill{
						position: absolute;
						top: -26rpx;
						left: -26rpx;
						color: #fff;
						font-size: 50rpx;
					}
					&.text-wrap-vertical{
						writing-mode:vertical-lr;
					}
				}
			}
		}
		.custom-step{
			position: absolute;
			top: 20rpx;
			left: 20rpx;
			.custom-step-item{
				height: 68rpx;
				line-height: 68rpx;
				display: flex;
				align-items: center;
				position: relative;
				&:not(:last-child)::before{
					// border-left: 2rpx solid red;
					content: '';
					width: 5rpx;
					height: 88%;
					background-color: $uni-color-primary;
					position: absolute;
					top: 56%;
					left: 5rpx;
				}
				.step-dot{
					width: 8rpx;
					height: 8rpx;
					border: 2rpx solid $uni-color-primary;
					border-radius: 50%;
					&.step-dot-checked{
						border-radius: 5rpx;
						background-color: $uni-color-primary;
						height: 6rpx;
					}
				}
				.step-name{
					color: #c5c5c5;
					margin-left: 10rpx;
					font-size: 24rpx;
					&.step-name-checked{
						color: $uni-color-primary;
					}
				}
			}
			.custom-step-icon{
				background-color: #fff;
				border-radius: 50%;
				width: 60rpx;
				height: 60rpx;
				display: flex;
				flex-direction: column;
				justify-content: center;
				align-items: center;
				.custom-step-icon-green{
					width: 10rpx;
					height: 10rpx;
					background-color: $uni-color-primary;
					border: 2rpx solid $uni-color-primary;
					border-radius: 50%;
				}
				.custom-step-icon-white{
					width: 10rpx;
					height: 10rpx;
					background-color: #fff;
					border: 2rpx solid $uni-color-primary;
					border-radius: 50%;
					margin-top: 6rpx;
				}
			}
		}
		.custom-appearence-desc{
			position: absolute;
			top: 20rpx;
			right: 0;
			background-color: #DDEEE4;
			border-radius: 50rpx 0 0 50rpx;
			padding: 12rpx 20rpx;
			.cuIcon-list{
				background-color: $uni-color-primary;
				border-radius: 50%;
				color: #fff;
				line-height: 1;
				padding: 8rpx;
				font-size: 24rpx;
			}
			.desc-text{
				margin-left: 6rpx;
			}
		}
	}
	.btn{
		width: 320rpx;
		border-radius: 50rpx;
		height: 80rpx;
		line-height: 80rpx;
		text-align: center;
		font-size: 30rpx;
		margin: 0 auto;
	}
	// 绿色按键
	.btn-green{
		border: 2rpx solid $uni-color-primary;
		background-color: $uni-color-primary;
		color: #fff;
	}
	// 白色按键
	.btn-white{
		color: $uni-color-primary;
		border: 2rpx solid $uni-color-primary;
	}
	.btn-grey{
		color: $color-grey;
		border: 2rpx solid $color-grey;
	}
	.control-panel{
		padding: 20rpx;
		.control-panel-step{
			.next-step{
				margin-top: 50rpx;
			}
			.function-btn{
				margin-top: 28rpx;
			}
		}
		.control-panel-step-1{
			.class-path{
				.class-path-item{
					
				}
			}
			.style-choose{
				.style-choose-item{
					display: inline-block;
					margin: 10rpx;
					.item-img-wrap{
						position: relative;
						.item-title{
							position: absolute;
							top: 10rpx;
							left: 20rpx;
							padding: 2rpx 8rpx;
							z-index: 2;
							font-size: 26rpx;
							background-color: rgba(0, 0, 0, .3);
							color: #fff;
							border-radius: 10rpx;
						}
						.item-check{
							position: absolute;
							bottom: 10rpx;
							right: 10rpx;
							z-index: 2;
							font-size: 42rpx;
							&.cuIcon-round{
								color: #eee;
							}
							&.cuIcon-roundcheckfill{
								color: $uni-color-primary;
								background-color: #fff;
								border-radius: 50%;
								line-height: 1;
								bottom: 16rpx;
							}
						}
						.item-img{
							width: 150rpx;
							height: 150rpx;
							border-radius: 10rpx;
						}
					}
					.item-hint{
						text-align: center;
						font-size: 26rpx;
					}
				}
			}
		}
		.control-panel-step-2{
			.step-2-img-wrap{
				// display: flex;
				// justify-content: center;
				// align-items: center;
				position: relative;
				.step-2-img-choose{
					background-color: $color-grey-light-double;
					width: 180rpx;
					height: 180rpx;
					border-radius: 16rpx;
					border: 2rpx dashed $color-grey;
					display: flex;
					flex-direction: column;
					justify-content: center;
					align-items: center;
					margin: 0 auto;
					position: relative;
					.cuIcon-add{
						font-size: 60rpx;
						color: $color-grey-dark;
					}
					.img-choose-text{
						color: $color-grey;
						font-size: 30rpx;
					}
					.step-2-img-choose-checked{
						width: 180rpx;
						height: 180rpx;
						border-radius: 16rpx;
					}
					.step-2-img-choose-checked-hint{
						position: absolute;
						bottom: 0;
						height: 36rpx;
						width: 180rpx;
						background-color: #ffffff99;
						z-index: 9;
						text-align: center;
						font-size: 26rpx;
					}
				}
				.step-2-img-rotate{
					position: absolute;
					color: $color-green;
					border-radius: 50%;
					box-shadow: 0 5rpx 30rpx -2rpx $color-green;
					line-height: 1;
					font-size: 42rpx;
					padding: 12rpx 12rpx 16rpx 18rpx;
					right: 118rpx;
					top: 50%;
					transform: translateY(-50%);
				}
			}
			.relative-hint-popup{
				text-align: center;
				color: $color-grey-dark-double;
				margin: 30rpx 0 10rpx 0;
				.cuIcon-info{
					color: $color-grey-dark-double;
					font-weight: bold;
					margin-right: 6rpx;
				}
			}
			.relative-hint{
				margin: 0 auto;
				text-align: center;
				width: 460rpx;
				color: $uni-color-primary;
				font-size: 30rpx;
			}
		}
		.control-panel-step-3{
			.step-3-tab{
				.step-3-tab-item{
					display: inline-block;
					width: 33%;
					text-align: center;
					.step-3-tab-item-text{
						display: inline-block;
						vertical-align: top;
						border-bottom: 5rpx solid #fff;
						height: 80rpx;
						padding-top: 8rpx;
						.icon-text{
							// color: $uni-color-primary;
							border: 5rpx dotted #333;
							font-size: 26rpx;
							padding: 6rpx;
							margin-right: 10rpx;
							position: relative;
							top: -5rpx;
							border-radius: 6rpx;
						}
						.icon-textColor{
							margin-right: 10rpx;
							padding: 6rpx;
							border: 5rpx solid #333;
							border-radius: 6rpx;
						}
						.icon-typesetting{
							margin-right: 10rpx;
							font-size: 50rpx;
							position: relative;
							top: 8rpx;
							line-height: .8;
						}
						&.step-3-tab-item-text-checked{
							color: $uni-color-primary;
							border-bottom: 5rpx solid $uni-color-primary;
							.icon-text{
								color: $uni-color-primary;
								border: 5rpx dotted $uni-color-primary;
							}
							.icon-textColor{
								color: $uni-color-primary;
								border: 5rpx solid $uni-color-primary;
							}
							.icon-typesetting{
								color: $uni-color-primary;
							}
						}
					}
				}
			}
			.step-3-tab-1{
				.step-3-add-text{
					margin: 60rpx 0;
					display: flex;
					height: 80rpx;
					justify-content: center;
					*{
						box-sizing: border-box;
					}
					.step-3-add-text-input-wrap{
						.step-3-add-text-input{
							height: 100%;
							background-color: #eee;
							border-radius: 50rpx 0 0 50rpx;
							text-align: center;
							border: 2rpx solid $color-grey;
						}
					}
					.step-3-add-text-check{
						background-color: $uni-color-primary;
						border-radius: 0 50rpx 50rpx 0;
						width: 118rpx;
						text-align: center;
						font-size: 50rpx;
						line-height: 80rpx;
						.cuIcon-check{
							color: #fff;
							position: relative;
							left: -5rpx;
						}
					}
				}
			}
			// 文字 和 排版公用css
			.adjust-text-angle,
			.adjust-text-size{
				display: flex;
				.adjust-text-left{
					position: relative;
					top: 20rpx;
					left: 20rpx;
					.adjust-text-title{
						color: #666;
					}
					.adjust-text-reset{
						color: $color-grey;
						border: 2rpx solid $color-grey;
						border-radius: 50rpx;
						text-align: center;
						width: 68rpx;
						font-size: 24rpx;
						line-height: 1;
						padding: 6rpx 8rpx;
						margin-top: 10rpx;
					}
				}
				.adjust-text-right{
					width: 568rpx;
					position: relative;
					left: 28rpx;
					.adjust-text-slider{
						
					}
					.adjust-text-angle-show,
					.adjust-text-size-show{
						width: 476rpx;
						margin: 0 auto;
						display: flex;
						justify-content: space-between;
						align-items: center;
						.adjust-text-angle-min,
						.adjust-text-size-min{
							
						}
						.adjust-text-angle-now,
						.adjust-text-size-now{
							
						}
						.adjust-text-angle-max,
						.adjust-text-size-max{
							
						}
					}
				}
			}
			.step-3-tab-2{
				.text-color{
					display: flex;
					margin-top: 60rpx;
					*{
						box-sizing: border-box;
					}
					.text-color-hint{
						width: 80rpx;
						color: #666;
					}
					.text-color-choose{
						width: 670rpx;
						.text-color-choose-item{
							display: inline-block;
							width: 80rpx;
							height: 80rpx;
							border-radius: 50%;
							margin: 0 20rpx 12rpx 20rpx;
							position: relative;
							&:first-child{
								border: 2rpx solid $color-grey !important;
							}
							&.text-color-choose-item-checked{
								border: 5rpx solid $uni-color-primary !important;
								box-shadow: 0 0 30rpx 0 $uni-color-primary;
								.white-circle{
									position: absolute;
									width: 72rpx;
									height: 72rpx;
									border: 6rpx solid #fff;
									border-radius: 50%;
								}
							}
						}
					}
				}
			}
			.step-3-tab-3{
				.step-3-text-orient{
					margin: 50rpx 0;
					display: flex;
					justify-content: space-between;
					.text-orient-item{
						border: 2rpx solid $color-grey-light-double;
						padding: 50rpx 0;
						border-radius: 100rpx;
						width: 320rpx;
						text-align: center;
						color: #999;
						position: relative;
						.text-orient-item-text{
							font-size: 32rpx;
							position: absolute;
							background-color: #fff;
							width: 220rpx;
							left: 50rpx;
							top: -10rpx;
							height: 116rpx;
							line-height: 116rpx;
						}
						&.text-orient-item-checked{
							.text-orient-item-text{
								color: $uni-color-primary;
							}
						}
					}
				}
			}
			.relative-hint-popup{
				text-align: center;
				color: $color-grey-dark-double;
				margin: 60rpx 0 10rpx 0;
				.cuIcon-info{
					color: $color-grey-dark-double;
					font-weight: bold;
					margin-right: 6rpx;
				}
			}
		}
		.control-panel-step-4{
			.cube-placeholder-wrap{
				text-align: center;
				margin: 60rpx 0;
				.cube-placeholder{
					width: 118rpx;
					height: 118rpx;
					line-height: 118rpx;
					font-size: 90rpx;
					border-radius: 50%;
					display: inline-block;
					color: $color-green;
					box-shadow: 0 6rpx 50rpx -5rpx $color-green;
				}
				.cube-placeholder-text{
					font-size: 30rpx;
					margin-top: 46rpx;
				}
			}
		}
	}
	.reset-template{
		background-color: #fff;
		width: 560rpx;
		border-radius: 12rpx;
		padding-top: 36rpx;
		.reset-template-title{
			text-align: center;
			font-size: 32rpx;
			letter-spacing: 2rpx;
		}
		.reset-template-subtitle{
			text-align: center;
			color: #666;
			margin: 20rpx 0 30rpx 0;
		}
		.reset-template-btn{
			border-top: 2rpx solid $color-grey-light-double;
			.reset-template-btn-item{
				display: inline-block;
				width: 280rpx;
				height: 80rpx;
				line-height: 80rpx;
				box-sizing: border-box;
				font-size: 36rpx;
				text-align: center;
			}
			.reset-template-btn-cancel{
				color: $uni-color-primary;
			}
			.reset-template-btn-check{
				border-left: 2rpx solid $color-grey-light-double;
			}
		}
	}
	.info-show{
		padding: 20rpx;
		background-color: #fff;
		border-radius: 16rpx 16rpx 0 0;
		.cuIcon-roundclosefill{
			text-align: right;
			font-size: 50rpx;
			color: $color-grey-dark;
		}
		.info-img-show{
			display: flex;
			.info-img{
				width: 466rpx;
			}
			.info-img-hint{
				position: relative;
				top: 45rpx;
				margin-left: 30rpx;
				font-size: 30rpx;
				color: #666;
			}
		}
		.info-show-hint{
			margin: 20rpx 0;
			letter-spacing: 2rpx;
			font-size: 30rpx;
		}
	}
</style>
