<style scoped>
	.col-md-6 {
		max-height: 500px;
		overflow-y: auto;
	}
</style>

<template>
	<div class="col-md-6">
		<div class="card card-school-presentation">
			<div class="card-header">
				<h4 class="card-title">School Presentation Schedule for {{year}}</h4>
			</div>
			<div class="card-body">
				<b-table responsive :items="presentations" :fields="fields">
					<template slot="school" slot-scope="data">
						<router-link :to="'/schools/' + getActiveYear + '/' + data.item.school_url_slug">{{data.value}}</router-link>
					</template>
					<template slot="dentist" slot-scope="data">
						<router-link v-if="data.value != null && data.value != ''" :to="'/dentists/' + data.item.dentist_url_slug">{{data.value}}</router-link>
						<span v-else>To be determined</span>
					</template>
					<template slot="presentation_date" slot-scope="data">
						<span v-if="data.value != null && data.value != ''">{{data.value}}</span>
						<span v-else>To be determined</span>
					</template>
				</b-table>
			</div>
		</div>
	</div>
</template>

<script>
	import {mapGetters} from 'vuex';

	export default {
		data() {
			return {
				presentations: [],
				fields: [
					{
						key: 'school',
						sortable: true,
						label: 'School'
					},
					{
						key: 'dentist',
						sortable: true,
						label: 'Dentist'
					},
					{
						key: 'presentation_date',
						sortable: true,
						label: 'Presentation Date'
					}
				]
			}
		},
		props: {
			year: Number
		},
		computed: {
			...mapGetters([
				'getActiveYear'
			])
		},
		created() {
			axios.get('/presentations/list').then((response) => {
				this.presentations = response.data.presentations;
				console.log(this.presentations)
			}).catch((error) => {
				console.log(error);
			})
		}
	}
</script>
