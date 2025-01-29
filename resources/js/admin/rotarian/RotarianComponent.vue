<style scoped>
	.card-title {
		display: inline;
	}
</style>

<template>
	<div class="main-panel">
		<header-title-component page_title="Rotarian"></header-title-component>
		<div class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">{{(rotarian != null) ? rotarian.rotarian_name : ''}}</h4>
							<button type="button" class="btn btn-success pull-right" @click="update=true" v-if="!update">Update</button>
							<button type="button" class="btn btn-warning pull-right" @click="update=false" v-else>Cancel</button>
						</div>
						<rotarian-update-component v-if="update"></rotarian-update-component>
						<rotarian-detail-component :rotarian="rotarian" v-else></rotarian-detail-component>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>


<script>
	import {eventBus} from '../../app';
	
	export default {
		data() {
			return {
				rotarian: null,
				update: false
			}
		},
		created() {
			this.getRotarian();
			eventBus.$on('refreshRotarian', () => {
				this.getRotarian();
			});
		},
		methods: {
			getRotarian() {
				axios.get('/rotarian/' + this.$route.params.rotarian).then((response) => {
					this.rotarian = response.data.rotarian;
				}).catch((error) => {
					console.log(error);
				});
			}
		}
	}
</script>