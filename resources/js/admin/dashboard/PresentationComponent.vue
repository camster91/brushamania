<template>
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">School Presentations Schedule for {{getActiveYear}}</h4>
					</div>
					<div class="card-body">
						<b-table responsive :items="presentations" :fields="fields">
							<template slot="school" slot-scope="data">
								<router-link :to="'/schools/' + data.item.school_url_slug">{{data.value}}</router-link>
							</template>
							<template slot="presentation_date" slot-scope="data">
								<span v-if="data.value != null && data.value != ''">{{data.value}}</span>
								<span v-else>To be determined</span>
							</template>
						</b-table>
					</div>
				</div>
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
						key: 'contact',
						sortable: true,
						label: 'Contact Person'
					},
					{
						key: 'email',
						sortable: true,
						label: 'Email'
					},
					{
						key: 'phone',
						sortable: true,
						label: 'Phone'
					},
					{
						key: 'presentation_date',
						sortable: true,
						label: 'Presentation Date'
					}
				]
			}
		},
		created() {
			axios.get('/presentations/list').then((response) => {
				this.presentations = response.data.presentations;
			}).catch((error) => {
				console.log(error);
			});
		},
		computed: {
			...mapGetters([
				'getActiveYear'
			])
		}
	}
</script>