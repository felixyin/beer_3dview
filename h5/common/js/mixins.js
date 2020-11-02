/**
 * @description 公用变量 && 公用函数
 * 
 */

/**
 * @description 获取页面数据
 * 
 */

export const requireData = {
	data() {
		return {
			currentPage: 1,						// 当前页码
			lastPage: 1,						// 最后一份数据页码
			data: ''							// 数据存储
		};
	},
	methods: {
		/**
		 * @description 访问数据接口
		 * @param {String} queryPath = [value] route.index.js 页面对应的键名
		 * @param {Object} queryData = [value] 访问数据接口需要的参数
		 */
		
		requireData: function(queryPath, queryData={}, ){
			let _this = this
			if(this.currentPage < this.lastPage){
				return this.commonJs.getData(queryPath, queryData)
			}else{
				// 没有数据了
				console.log('there is no data.')
				return { then: function(foo){ foo('') } }
			}
		},
		
		
	}
}