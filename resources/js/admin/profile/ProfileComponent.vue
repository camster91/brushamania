<style scoped>
	.card-title {
		display: inline;
	}
</style>

<template>
	<div class="main-panel">
		<header-title-component page_title="User Profile"></header-title-component>
		<div class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<profile-tab-component></profile-tab-component>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>


<script>
	import {eventBus} from '../../app';
	
	export default {}
</script>