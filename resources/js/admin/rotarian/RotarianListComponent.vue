<template>
    <div class="main-panel">
        <header-title-component page_title="Rotarians">
        	<template v-slot:year>
	        	<select name="year" class="form-control col-md-3" v-model="selected_year" @change="updateRotarianList()">
	        		<option v-for="year in getYears" :value="year">{{year}}</option>
	        	</select>
	        </template>
	        <template v-slot:export>
        		<a :href="'/rotarians/export/' + selected_year" class="btn btn-success">Export</a>
        	</template>
        	<template v-slot:addNew>
        		<router-link class="btn btn-primary" to="/rotarians/new">Add New Rotarian</router-link>
        	</template>
        </header-title-component>
		<content-component title="Rotarians List" search_placeholder="Search Rotarian" :fields="fields" :items="getRotarians" :total_rows="getRotariansLength" :selected_year="selected_year"></content-component>
    </div>
</template>

<script>
	import {mapGetters} from 'vuex';
	import {eventBus} from '../../app';
	
	export default {
		data() {
			return {
				search: '',
				selected_year: null,
				fields: [
					{
						key: 'rotarian_name',
						sortable: true,
						label: 'Rotarian'
					},
										{
						key: 'club',
						sortable: true,
						label: 'Rotary Club'
					},
					{
						key: 'email',
						sortable: true,
						label: 'Email'
					},
					{
						key: 'assigned_school',
						sortable: true,
						label: 'Assigned School'
					},
					{
						key: 'assigned_school_presentation_date',
						sortable: true,
						label: 'Presentation Date'
					}
				],
			}
		},
		computed: {
			...mapGetters([
				'getRotarians',
				'getRotariansLength',
				'getYears',
				'getActiveYear'
			])
		},
		created() {
			eventBus.$on('deleteRotarian', (id) => {
				this.deleteRotarian(id);
			});
			this.setYear();
		},
		beforeRouteLeave(to, from, next) {
			if(this.getActiveYear != this.selected_year) {
				this.$store.dispatch('getRotarians');
			}
			next();
		},
		methods: {
			deleteRotarian(id) {
				swal({
					title: "Are you sure?",
					text: "Once deleted, you will never be able to recover this rotarian!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((willDelete) => {
					if(willDelete) {
						axios.delete('/rotarian/' + id).then((response) => {
							swal(response.data.msg, {
								icon: 'success'
							});
							this.$store.dispatch('getRotarians', this.selected_year);
							this.$store.dispatch('getSchools');
						}).catch((error) => {
							swal(error.response.data.msg, {
								icon: 'error'
							});
						});
					}
				});
			},
			updateRotarianList() {
				this.$store.dispatch('getRotarians', this.selected_year);
			},
			setYear() {
				axios.get('/year/active').then((response) => {
					this.selected_year = response.data.year;
				}).catch((error) => {
					console.log(error);
				})
			}
		}
	}
</script>