<style scoped>
	#import-dentists {
		display: contents;
	}
	#import-dentists input {
		display: inline;
	}
	#import-dentists #file {
		margin-left: 20px;
		margin-right: 10px;
	}
</style>

<template>
    <div class="main-panel">
        <header-title-component page_title="Dentists">
        	<template v-slot:year>
	        	<select name="year" class="form-control col-md-3" v-model="selected_year" @change="updateDentistList()">
	        		<option v-for="year in getYears" :value="year" :key="year">{{year}}</option>
	        	</select>
	        </template>
	        <template v-slot:export>
        		<a :href="'/dentists/export/' + selected_year" class="btn btn-success">Export</a>
        	</template>
        	<template v-slot:import>
        		<form @submit.prevent="importDentist" method="POST" enctype="multipart/form-data" id="import-dentists">
        			<input type="file" name="file" class="form-control" id="file" ref="file" @change="handleFileUpload()">
        			<input type="submit" value="Import" class="btn btn-primary">
        		</form>
        	</template>
        	<template v-slot:addNew>
        		<router-link class="btn btn-primary" to="/dentists/new">Add New Dentist</router-link>
        	</template>
        </header-title-component>
		<content-component title="Dentists List" search_placeholder="Search Dentist" :fields="fields" :items="getDentists" :total_rows="getDentistsLength" :selected_year="selected_year"></content-component>
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
						key: 'dentist_name',
						sortable: true,
						label: 'Dentist'
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
				'getDentists',
				'getDentistsLength',
				'getYears',
				'getActiveYear'
			])
		},
		created() {
			eventBus.$on('deleteDentist', (id) => {
				this.deleteDentist(id);
			});
			this.setYear();
		},
		beforeRouteLeave(to, from, next) {
			if(this.getActiveYear != this.selected_year) {
				this.$store.dispatch('getDentists');
			}
			next();
		},
		methods: {
			deleteDentist(id) {
				swal({
					title: "Are you sure?",
					text: "Once deleted, you will never be able to recover this dentist!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((willDelete) => {
					if(willDelete) {
						axios.delete('/dentist/' + id).then((response) => {
							swal(response.data.msg, {
								icon: 'success'
							});
							this.$store.dispatch('getDentists', this.selected_year);
							this.$store.dispatch('getSchools', this.selected_year);
						}).catch((error) => {
							swal(error.response.data.msg, {
								icon: 'error'
							});
						});
					}
				});
			},
			updateDentistList() {
				this.$store.dispatch('getDentists', this.selected_year);
			},
			setYear() {
				axios.get('/year/active').then((response) => {
					this.selected_year = response.data.year;
				}).catch((error) => {
					console.log(error);
				})
			},
			handleFileUpload() {
				this.file = this.$refs.file.files[0];
			},
			importDentist() {
				let formData = new FormData();
				formData.append('file', this.file);
				axios.post('/dentists/import', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					}
				}).then((response) => {
					this.$store.dispatch('getDentists');
					swal("Dentists Are Being Added", {
						icon: "success"
					});
				}).catch((error) => {
					this.backend_errors = error.response.data.errors;
                        swal(error.response.data.msg, {
							icon: 'error'
						});
				})
			},
		}
	}
</script>