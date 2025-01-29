<template>
	<li>
		<router-link :to="item.link">
			<i :class="item.classicon"></i>
			<p>{{item.name}}</p>
		</router-link>
	</li>
</template>

<script>
	export default {
		props: {
			item: Object
		}
	}
</script>