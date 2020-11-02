/**
 * @description 页面路由
 */

export default {
	// 首页
	index: {
		name: '首页',
		path: '/pages/index/index'
	},
	themeList: {
		name: '主体列表（分类列表）',
		path: '/pages/index/themeLsit'
	},
	commodityList: {
		name: '商品列表',
		path: '/pages/index/commodityList'
	},
	// 定制步骤
	customStep: {
		name: '包装定制页面',
		path: '/pages/index/custom/customStep'
	},
	customDemo: {
		name: '3D模型展示页面',
		path: '/pages/index/custom/customDesc'
	},
	customDesc: {
		name: '产品包装说明',
		path: '/pages/index/custom/customDesc'
	},
	// 用户中心
	user: {
		name: '用户中心',
		path: '/pages/user/user'
	},
	service: {
		name: '联系客服',
		path: '/pages/user/service'
	},
	userinfo: {
		name: '个人资料',
		path: '/pages/user/userinfo'
	}
}