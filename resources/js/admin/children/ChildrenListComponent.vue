<template>
    <div class="main-panel">
        <header-title-component page_title="Children">
        	<template v-slot:year>
	        	<select name="year" class="form-control col-md-3" v-model="selected_year" @change="updateChildrenList()">
	        		<option v-for="year in getYears" :value="year" :key="year">{{year}}</option>
	        	</select>
	        </template>
	        <template v-slot:export>
        		<a :href="'/children/export/' + selected_year" class="btn btn-success">Export</a>
        	</template>
			<template v-slot:import>
        		<input type="button" value="Send Certificates" class="btn btn-primary" @click.prevent="sendCertificateAll">
        	</template>
        </header-title-component>
		<content-component title="Children List" search_placeholder="Search Children" :fields="fields" :items="getChildren" :total_rows="getChildrenLength">
		</content-component>
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
						key: 'child_name',
						sortable: true,
						label: 'Child'
					},
										{
						key: 'child_parent.name',
						sortable: true,
						label: 'Parent'
					},
					{
						key: 'brush_count',
						sortable: true,
						label: 'Progress'
					},
					{
						key: 'certificate_status',
						sortable: true,
						label: 'Certificate Status'
					}
				],
			}
		},
		computed: {
			...mapGetters([
				'getChildren',
				'getChildrenLength',
				'getYears',
				'getActiveYear'
			])
		},
		created() {
			eventBus.$on('deleteChild', (id) => {
				this.deleteChild(id);
			});
			eventBus.$on('sendCertificate', (id) => {
				this.sendCertificate(id);
			});
			this.setYear();
		},
		beforeRouteLeave(to, from, next) {
			if(this.getActiveYear != this.selected_year) {
				this.$store.dispatch('getChildren');
			}
			next();
		},
		methods: {
			sendCertificateAll() {
				axios.post('/certificate-all', {
					year: this.selected_year
				}).then((response) => {
					console.log(response.data);
					swal('Certificate Sent', {
						icon: 'success'
					});
				}).catch((error) => {
					console.log(error.response.data);
					swal(error.response.data.msg, {
						icon: 'error'
					});
				});
			},
			deleteChild(id) {
				swal({
					title: "Are you sure?",
					text: "Once deleted, you will never be able to recover this child!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((willDelete) => {
					if(willDelete) {
						axios.delete('/child/' + id).then((response) => {
							swal(response.data.msg, {
								icon: 'success'
							});
							this.$store.dispatch('getChildren');
						}).catch((error) => {
							swal(error.response.data.msg, {
								icon: 'error'
							});
						});
					}
				});
			},
			updateChildrenList() {
				this.$store.dispatch('getChildren', this.selected_year);
			},
			setYear() {
				axios.get('/year/active').then((response) => {
					this.selected_year = response.data.year;
				}).catch((error) => {
					console.log(error);
				})
			},
			sendCertificate(id) {
				axios.post('/certificate', {
					id: id
				}).then((response) => {
					swal('Certificate Sent', {
						icon: 'success'
					});
					// this.$store.dispatch('getChildren');
				}).catch((error) => {
					swal(error.response.data.msg, {
						icon: 'error'
					});
				});
			}
		}
	}
</script>