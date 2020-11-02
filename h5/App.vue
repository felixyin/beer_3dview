<script>
	export default {
		onLaunch: function() {
			console.log('App Launch')
		},
		onShow: function() {
			console.log('App Show')
		},
		onHide: function() {
			console.log('App Hide')
		},
		globalData: {
			baseURL: 'https://js.lsqspace.com/api/content/',
			token: '5dc08e95b07e239da6f1457e9ba608b0d3b406d3d1394f80f1d083dacd87beed'
		}
	}
</script>

<style lang="scss">
	/*每个页面公共css */
	@import url('./common/style/colorui/icon.css');
	@import url('./common/style/common.scss');
	@import url('http://at.alicdn.com/t/font_2149563_pete143lf2s.css');
	
	/* uni 样式修改 */
	uni-slider .uni-slider-thumb{
		border: 5rpx solid #009A44;
		top: 0;
	}
</style>
