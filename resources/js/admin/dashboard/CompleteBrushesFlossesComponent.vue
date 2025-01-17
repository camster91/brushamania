<style scoped>
	.col-md-6 {
		max-height: 500px;
		overflow-y: auto;
	}	
</style>

<template>
	<div class="col-md-6">
		<div class="card card-complete-brushes-flosses">
			<div class="card-header">
				<h4 class="card-title">Children With 100 Brushes and Flosses for {{year}}</h4>
			</div>
			<div class="card-body">
				<b-table responsive :items="children" :fields="fields">
					<template slot="child" slot-scope="data">
						<router-link :to="'/children/' + data.item.child_url_slug">{{data.value}}</router-link>
					</template>
					<template slot="parent" slot-scope="data">
						<router-link :to="'/parents/' + data.item.parent_url_slug">{{data.value}}</router-link>
					</template>
				</b-table>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				children: [],
				fields: [
					{
						key: 'child',
						sortable: true,
						label: 'Child'
					},
					{
						key: 'parent',
						sortable: true,
						label: 'Parent'
					},
					{
						key: 'completed_on',
						sortable: true,
						label: 'Completed On'
					}
				]
			}
		},
		props: {
			year: Number
		},
		created() {
			this.getComplete();
		},
		methods: {
			getComplete() {
				axios.get('/brushtrackers/complete').then((response) => {
					this.children = response.data.children;
				}).catch((error) => {
					console.log(error);
				})
			}
		}
	}
</script>