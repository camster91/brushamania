<template>
	<div class="row">
		<stats-component icon="nc-hat-3" category="Schools" :title="getSchoolsLength" :year="year"></stats-component>
		<stats-component icon="nc-satisfied" category="Students" :title="getStudentsCount" :year="year"></stats-component>
		<stats-component icon="nc-badge" category="Dentists" :title="getDentistsLength" :year="year"></stats-component>
		<stats-component icon="nc-briefcase-24" category="Rotarians" :title="getRotariansLength" :year="year"></stats-component>
		<!-- <stats-component icon="nc-single-02" category="Parents" :title="getParentsLength" :year="year"></stats-component> -->
		<stats-component icon="nc-satisfied" category="Children" :title="getChildrenLength" :year="year"></stats-component>
	</div>
</template>

<script>
	import {mapGetters} from 'vuex';

	export default {
		props: {
			year: Number
		},
		computed: {
			...mapGetters([
				'getSchoolsLength',
				'getDentistsLength',
				'getRotariansLength',
				'getParentsLength',
				'getChildrenLength',
				'getStudentsCount'
			])
		}
	}
</script>