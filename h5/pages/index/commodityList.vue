<template>
	<view class="commodity-list-wrap">
		<!-- 商品推荐 || 全部 || 筛选 -->
		<view class="filter-wrap">
			<view>
				<view class="filter-btn filter-recommend" :class="{'filter-btn-checked': curTabIndex === 0}" @tap="changeTab(0)">推荐</view>
				<view class="filter-btn filter-all" :class="{'filter-btn-checked': curTabIndex === 1}" @tap="changeTab(1)">全部</view>
			</view>
			<view class="filter-btn filter-open-panel cuIcon-filter" @tap="openPanel('filter')">筛选</view>
		</view>
		<!-- 商品列表 -->
		<view class="commodity-list">
			<view class="commodity-list-item" v-for="(item, index) in commodityList" :key="item.id" @tap="togo('customStep', item)">
				<image class="item-img" :src="item.img"></image>
				<view class="item-title nowrap2">{{ item.title }}</view>
				<view class="item-price">零售价：￥{{ item.price }}</view>
				<view class="item-unit">({{ item.unit }})</view>
				<view class="item-class">{{ item.type == '1' ? '图文版':'文字版'}}</view>
			</view>
		</view>
		<uni-popup ref="filterPanel" type="bottom">
			<view class="filter-panel">
				<view class="filter-panel-top">
					<view class="filter-panel-cancel" @tap="panelBtn('filter', 'cancel')">取消</view>
					<view class="filter-panel-title">选择风格</view>
					<view class="filter-panel-check" @tap="panelBtn('filter', 'check')">确认</view>
				</view>
				<view class="filter-panel-content">
					<!-- 风格 -->
					<view class="filter-panel-content-item filter-panel-content-style">
						<view class="item-title style-title">风格</view>
						<view 
							class="item-content style-item" 
							:class="{'item-content-checked': curCommodityStyle == index}" 
							v-for="(item, index) in commodityStyle" :key="item.id"
							@tap="changeType('style', item, index)"
						>
							{{ item.name }}
						</view>
					</view>
					<!-- 类型 -->
					<view class="filter-panel-content-item filter-panel-content-type">
						<view class="item-title type-title">类型</view>
						<view 
							class="item-content type-item" 
							:class="{'item-content-checked': curCommodityClass == index}" 
							v-for="(item, index) in commodityClass" :key="item.id"
							@tap="changeType('class', item, index)"
						>
							{{ item.name }}
						</view>
					</view>
				</view>
			</view>
		</uni-popup>
		<customer-service></customer-service>
	</view>
</template>

<script>
	import UniPopup from '../../components/uni-popup/uni-popup.vue'
	export default {
		components: {
			UniPopup
		},
		onShow() {
			
		},
		onReachBottom() {
			console.log('[+] there is no data.')
		},
		data() {
			return {
				lastPage: 1,
				currentPage: 0,
				curTabIndex: 0,									// 当前选中的TAB index
				curCommodityStyle: 0,							// 当前选中的商品风格
				curCommodityClass: 0,							// 当前选中的商品类型
				commodityStyle: [{								// 商品风格
					id: 0,
					name: '全部'
				},{
					id: 1,
					name: '复古婚礼'
				},{
					id: 2,
					name: '中式婚礼'
				},{
					id: 3,
					name: '森系婚礼'
				},{
					id: 4,
					name: '韩式婚礼'
				},{
					id: 5,
					name: '欧式婚礼'
				}],
				commodityClass: [{								// 商品类型
					id: 0,
					name: '全部'
				},{
					id: 1,
					name: '图文版'
				},{
					id: 2,
					name: '文字版'
				}],
				commodityList: [{								// 商品内容
					id: 0,
					img: '../../static/commodity0.png',
					title: '深海蓝',
					price: '239.00',
					unit: '箱/24罐',
					type: '1',			// 0 文字版 1 图文版
				},{
					id: 1,
					img: '../../static/commodity1.png',
					title: '相聚时刻',
					price: '189.00',
					unit: '箱/24罐',
					type: '0',			// 0 文字版 1 图文版
				},{
					id: 2,
					img: '../../static/commodity2.png',
					title: '感谢有你',
					price: '189.00',
					unit: '箱/24罐',
					type: '0',			// 0 文字版 1 图文版
				},{
					id: 3,
					img: '../../static/commodity0.png',
					title: '相聚时刻',
					price: '189.00',
					unit: '箱/24罐',
					type: '0',			// 0 文字版 1 图文版
				},{
					id: 4,
					img: '../../static/commodity1.png',
					title: '感谢有你',
					price: '189.00',
					unit: '箱/24罐',
					type: '0',			// 0 文字版 1 图文版
				}]
			};
		},
		methods: {
			getData: function(){
				let _this = this
				if(this.currentPage < this.lastPage){
					this.commonJs.getData('themeList', {
						page: this.currentPage+1,
						search: '',
						cat_id: '',						// 分类id
						filter: '',						// 筛选 0 推荐 1 全部 
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
			 * @description 切换TAB
			 * @param {Number} type = [0|1] 0 推荐 1 全部
			 */
			
			changeTab: function(type){
				this.curTabIndex = type
				if(type === 0){
					// 更新推荐数据
				}else if(type === 1){
					// 更新全部数据
				}
			},
			
			/**
			 * @description 弹出面板
			 * @param {String} type = ['filter'] filter 筛选面板 
			 */
			
			openPanel: function(type){
				if(type === 'filter') this.$refs.filterPanel.open()
			},
			
			/**
			 * @description 关闭面板
			 * @param {String} type = ['filter'] filter 筛选面板 
			 */
			
			closePanel: function(type){
				if(type === 'filter') this.$refs.filterPanel.close()
			},
			
			/**
			 * @description 切换风格
			 * @param {String} type = ['type'|'class'] type 选择风格 class 选择类型
			 * @param {Object} item = [item] 选中的风格对象
			 * @param {Number} index = [index] 选中的风格index   
			 */
			
			changeType: function(type, item, index){
				if(type == 'style'){
					this.curCommodityStyle = index
				}else if(type == 'class'){
					this.curCommodityClass = index
				}
			},
			
			/**
			 * @description 弹出面板的按键事件
			 * @param {String} origin = ['filter'] filter 筛选面板
			 * @param {String} type = ['cancel'|'check'] cancel 取消 check 确认  
			 */
			
			panelBtn: function(origin, type){
				if(origin == 'filter'){
					if(type == 'cancel'){
						
					}else if(type == 'check'){
						// 更新筛选数据
					}
					this.closePanel('filter')
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
	.commodity-list-wrap{
		padding: 0 20rpx;
		background-color: $color-grey-light-double;
		min-height: 100vh;
		overflow: hidden;
	}
	.filter-wrap{
		margin: 80rpx 0 30rpx 0;
		display: flex;
		justify-content: space-between;
		.filter-btn{
			height: 80rpx;
			line-height: 80rpx;
			width: 190rpx;
			border: 2rpx solid $color-grey-light;
			color: $color-grey;
			border-radius: 50rpx;
			text-align: center;
			font-size: 30rpx;
			&.filter-recommend{
				display: inline-block;
			}
			&.filter-all{
				display: inline-block;
				margin-left: 20rpx;
			}
			&.filter-open-panel{
				
			}
			&.filter-btn-checked{
				background-color: $uni-color-primary;
				border: 2rpx solid $uni-color-primary;
				color: #fff;
			}
		}
	}
	.commodity-list{
		.commodity-list-item{
			width: 338rpx;
			display: inline-block;
			border-radius: 20rpx;
			margin: 8rpx;
			padding: 50rpx 20rpx 30rpx 20rpx;
			background-color: #fff;
			position: relative;
			.item-img{
				width: 298rpx;
				height: 298rpx;
				border-radius: 20rpx 20rpx 0 0;
				border-bottom: 2rpx solid $color-grey-light;
			}
			.item-title{
				width: 298rpx;
				height: 100rpx;
				font-size: 32rpx;
				padding-top: 10rpx;
			}
			.item-price{
				color: $color-red;
				font-size: 30rpx;
			}
			.item-unit{
				color: $color-grey;
				margin-top: 10rpx;
				font-size: 26rpx;
			}
			.item-class{
				position: absolute;
				top: 20rpx;
				right: 20rpx;
				background-color: $color-grey-light;
				color: #fff;
				border-radius: 6rpx;
				line-height: 1;
				padding: 6rpx 10rpx;
			}
		}
	}
	.filter-panel{
		background-color: #fff;
		width: 750rpx;
		min-height: 800rpx;
		padding: 30rpx;
		* {
			font-size: 35rpx;
		}
		.filter-panel-top{
			display: flex;
			justify-content: space-between;
			.filter-panel-cancel{
				color: $color-grey;
			}
			.filter-panel-title{
				
			}
			.filter-panel-check{
				color: $uni-color-primary;
			}
		}
		.filter-panel-content{
			padding-top: 68rpx;
			.filter-panel-content-item{
				padding-bottom: 60rpx;
				.item-title{
					margin-bottom: 20rpx;
				}
				.item-content{
					height: 86rpx;
					width: 200rpx;
					display: inline-block;
					text-align: center;
					line-height: 80rpx;
					border: 2rpx solid $color-grey;
					border-radius: 50rpx;
					margin: 20rpx 20rpx 0 0;
					&.item-content-checked{
						color: #fff;
						border: 2rpx solid $uni-color-primary;
						background-color: $uni-color-primary;
					}
				}
				&.filter-panel-content-style{
					
				}
				&.filter-panel-content-class{
					
				}
			}
		}
	}
</style>
