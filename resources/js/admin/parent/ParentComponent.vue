<style scoped>
	.card-title {
		display: inline;
	}
</style>

<template>
	<div class="main-panel">
		<header-title-component page_title="Parent"></header-title-component>
		<div class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">{{parent}}</h4>
						</div>
						<parent-update-component></parent-update-component>
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
				parent: null
			}
		},
		created() {
			this.getParent();
			eventBus.$on('refreshParent', () => {
				this.getParent();
			});
		},
		methods: {
			getParent() {
				axios.get('/parent/' + this.$route.params.parent).then((response) => {
					this.parent = response.data.parent;
				}).catch((error) => {
					console.log(error);
				});
			}
		}
	}
</script>