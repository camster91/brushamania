<template>
	<school-detail-component :school="school">
		<h2 class="title">{{school.school_name}}</h2>
	</school-detail-component>
</template>

<script>
	export default {
		props: {
			school: Object
		},
		created() {
			console.log(this.school);
		},
		updated() {
			console.log(this.school);
		}
	}
</script>