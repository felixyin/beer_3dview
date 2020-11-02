/**
 * @description 常用方法
 * 
 */
import App from '../../App.vue'
import Http from './http.js'
import Route from '../../config/route.index.js'
import Api from '../../config/api.index.js'
export default {
	/**
	 * @description 获取数据接口数据
	 */
	
	getData: function(path, data={}){
		data.token = App.globalData.token
		return Http({
			url: Api[path].path,
			method: 'GET',
			data: data,
			code: '1',
		})
	},
	postData: function(path, data={}){
		data.token = App.globalData.token
		return Http({
			url: Api[path].path,
			method: 'POST',
			data: data,
			code: '1',
		})
	},
	
	/**
	 * @description 页内路由
	 * @param {String}  = [pathName] route.index.js 页面对应的键名
	 * @param {Object}  = [data] 路由跳转时携带的数据 
	 */
	
	togo: function(pathName, data={}){
		let dataTemp = ''
		for(let m in data){
			dataTemp += m + '=' + data[m] + '&'
		}
		uni.navigateTo({
			url: Route[pathName].path + '?' + dataTemp.slice(0, -1),
		})
	}
}