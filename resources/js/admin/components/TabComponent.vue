<style scoped>
	.nav-item {
		max-width: 100%;
	}
</style>
<template>
	<li class="nav-item">
		<a :href="link" class="nav-link" :class="is_active ? 'active' : ''" :id="tab_id" :data-toggle="toggle" :aria-controls="controls" :aria-selected="is_active">{{title}}</a>
	</li>
</template>

<script>
	export default {
		props: {
			link: String,
			is_active: Boolean,
			tab_id: String,
			controls: String,
			title: String,
			toggle: String
		}
	}
</script>