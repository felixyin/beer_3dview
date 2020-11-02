<template>
	<view>
		123
	</view>
</template>

<script>
	export default {
		data() {
			return {
				currentPage: 0,						// 当前页码
				lastPage: 1,						// 最后一份数据页码
			};
		},
		methods: {
			/**
			 * @description 获取数据
			 * 
			 */
			
			getData: function(){
				let _this = this
				if(this.currentPage < this.lastPage){
					this.commonJs.getData('themeList', {
						page: this.currentPage+1,
						search: ''
					}).then(res => {
						_this.lastPage = res.data.cat.last_page
						_this.currentPage = res.data.cat.current_page
						_this.data = res.data.cat.data
					})
				}else{
					// 没有数据了
					console.log('there is no data.')
				}
			}
		}
	}
</script>

<style lang="scss">

</style>
