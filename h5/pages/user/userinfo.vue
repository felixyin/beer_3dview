<template>
	<view>
		<view v-for="(item, index) in data" :key="item.id">
			<setting-item :data="item"></setting-item>
		</view>
		<btn-collections class="save-btn">保存</btn-collections>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				data: [{
					id: 0,
					type: 'show',
					title: '手机号',
					content: '13888888888'
				},{
					id: 1,
					type: 'input',
					title: '客户昵称',
					placeholder: '请输入客户昵称'
				},{
					id: 2,
					type: 'input',
					title: '客户姓名',
					placeholder: '请输入客户姓名'
				},{
					id: 3,
					type: 'selectSingle',
					title: '性别',
					data: ['男', '女', '未知'],
					banner: '未知'
				},{
					id: 4,
					type: 'selectDate',
					title: '出生日期',
					banner: '1970-01-01',
					startDate: '1910-01-01',
					endDate: new Date().now.f1,
				},{
					id: 5,
					type: 'selectSingle',
					title: '所属行业',
					data: ['电子商务', '游戏', '媒体', '广告营销', '数据服务', '医疗健康', '在线教育', '社交网络', '企业服务'],
					banner: '请选择'
				},{
					id: 6,
					type: 'input',
					title: '客户职位',
					placeholder: '请输入客户职位'
				}]
			};
		}
	}
</script>

<style lang="scss" scoped>
	.save-btn{
		margin-top: 60rpx;
	}
</style>
