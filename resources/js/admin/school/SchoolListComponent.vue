<style scoped>
	form {
		display: inline-flex;
	}
	form .btn {
		margin: auto !important;
	}
</style>

<template>
    <div class="main-panel">
        <header-title-component page_title="Schools">
        	<template v-slot:year>
        		<select name="year" class="form-control col-md-2" v-model="selected_year" @change="updateSchoolList()">
	        		<option v-for="year in getYears" :value="year" :key="year">{{year}}</option>
					<option :value="0">All</option>
	        	</select>
        	</template>
        	<template v-slot:export>
        		<a :href="'/schools/export/' + selected_year" class="btn btn-success">Export</a>
        	</template>
			<template v-slot:masterList>
        		<a :href="'/school-master-list'" class="btn btn-success">Export Master List</a>
        	</template>
			<template v-slot:import>
        		<form @submit.prevent="importSchools" method="POST" enctype="multipart/form-data" id="import-schools">
        			<input type="file" name="file" class="form-control" id="file" ref="file" @change="handleFileUpload()">
        			<input type="submit" value="Import" class="btn btn-primary">
        		</form>
        	</template>
			<template v-slot:addNewUnregistered>
        		<router-link class="btn btn-primary" to="/schools/new">Add New School</router-link>
        	</template>
        	<template v-slot:addNew>
        		<router-link class="btn btn-primary" to="/schools/new-unregistered">Add New Unregistered School</router-link>
        	</template>
        </header-title-component>
		<content-component title="Schools List" search_placeholder="Search School" :fields="fields" :items="getSchools" :total_rows="getSchoolsLength" :selected_year="selected_year"></content-component>
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
						key: 'school_name',
						sortable: true,
						label: 'School'
					},
					{
						key: 'email',
						sortable: true,
						label: 'Email'
					},
					{
						key: 'presentation.total_classes',
						sortable: true,
						label: 'Total Classes'
					},
					{
						key: 'presentation.total_students',
						sortable: true,
						label: 'Total Students'
					},
					{
						key: 'presentation.dentist',
						sortable: true,
						label: 'Dentist'
					},
					{
						key: 'presentation.rotarian',
						sortable: true,
						label: 'Rotarian'
					},
					{
						key: 'presentation.presentation_date',
						sortable: true,
						label: 'Presentation Date'
					}
				]
			}
		},
		computed: {
			...mapGetters([
				'getSchools',
				'getSchoolsLength',
				'getYears',
				'getActiveYear'
			])
		},
		created() {
			eventBus.$on('deleteSchool', (id) => {
				this.deleteSchool(id);
			});
			this.setYear();
		},
		beforeRouteLeave(to, from, next) {
			if(this.getActiveYear != this.selected_year) {
				this.$store.dispatch('getSchools');
			}
			next();
		},
		methods: {
			deleteSchool(id) {
				swal({
					title: "Are you sure?",
					text: "Once deleted, you will never be able to recover this school!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((willDelete) => {
					if(willDelete) {
						axios.delete('/school/' + id).then((response) => {
							swal(response.data.msg, {
								icon: 'success'
							});
							this.$store.dispatch('getSchools', this.selected_year);
							this.$store.dispatch('getRotarians');
							this.$store.dispatch('getDentists');
						}).catch((error) => {
							swal(error.response.data.msg, {
								icon: 'error'
							});
						});
					}
				});
			},
			updateSchoolList() {
				this.$store.dispatch('getSchools', this.selected_year);
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
			importSchools() {
				let formData = new FormData();
				formData.append('file', this.file);
				axios.post('/schools/import', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					}
				}).then((response) => {
					this.$store.dispatch('getSchools');
					swal("Schools Are Being Added", {
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