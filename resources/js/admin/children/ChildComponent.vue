<style scoped>
	.card-title {
		display: inline;
	}
</style>

<template>
	<div class="main-panel">
		<header-title-component page_title="Child"></header-title-component>
		<div class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">{{child}}</h4>
						</div>
						<child-update-component></child-update-component>
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
				child: null
			}
		},
		created() {
			this.getChild();
			eventBus.$on('refreshChild', () => {
				this.getChild();
			});
		},
		methods: {
			getChild() {
				axios.get('/child/' + this.$route.params.child).then((response) => {
					this.child = response.data.child;
				}).catch((error) => {
					console.log(error);
				});
			}
		}
	}
</script>