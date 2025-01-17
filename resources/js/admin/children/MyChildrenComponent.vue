<template>
	<div class="main-panel">
		<header-title-component page_title="Children">
			<template v-slot:addNew>
        		<router-link class="btn btn-primary" to="/add-children">Add Children</router-link>
        	</template>
		</header-title-component>
		<div class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">My Children</h4>
						</div>
						<my-children-tab-component></my-children-tab-component>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {}
</script>