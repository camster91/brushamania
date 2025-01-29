<style scoped>
	#import-politicians {
		display: contents;
	}
	#import-politicians input {
		display: inline;
	}
	#import-politicians #file {
		margin-left: 20px;
		margin-right: 10px;
	}
</style>

<template>
    <div class="main-panel">
        <header-title-component page_title="Politicians">
        	<template v-slot:addNew>
        		<button class="btn btn-primary" @click="showModal()">Add New Politician</button>
        	</template>
        	<template v-slot:export>
        		<a href="/politicians/export" class="btn btn-success">Export</a>
        	</template>
        	<template v-slot:import>
        		<form @submit.prevent="importPolitician" method="POST" enctype="multipart/form-data" id="import-politicians">
        			<input type="file" name="file" class="form-control" id="file" ref="file" @change="handleFileUpload()">
        			<input type="submit" value="Import" class="btn btn-primary">
        		</form>
        	</template>
        </header-title-component>
		<content-component title="Politicians List" search_placeholder="Search Politician" :fields="fields" :items="getPoliticians" :total_rows="getPoliticiansLength"></content-component>
		<modal-component modal_id="add-politician-modal" title="Add User">
			<form @submit.prevent="submitPolitician" method="POST">
				<div class="modal-body">
					<div class="row row-space">
						<div class="col-md-6">
							<label class="label">First Name</label>
							<input type="text" class="form-control" name="politician_firstname" required v-validate data-vv-as="first name" v-model="politician.firstname">
		                    <div class="invalid-feedback">
		                        {{errors.first('politician_firstname')}}
		                        {{(backend_errors != null) ? (backend_errors.firstname != null) ? backend_errors.firstname[0] : null : null}}
		                    </div>
						</div>
						<div class="col-md-6">
							<label class="label">Last Name</label>
							<input type="text" class="form-control" name="politician_lastname" required v-validate data-vv-as="first name" v-model="politician.lastname">
		                    <div class="invalid-feedback">
		                        {{errors.first('politician_lastname')}}
		                        {{(backend_errors != null) ? (backend_errors.lastname != null) ? backend_errors.lastname[0] : null : null}}
		                    </div>
						</div>
					</div>
					<div class="row row-space">
						<div class="col-md-6">
							<label class="label">Title</label>
							<input type="text" class="form-control" name="politician_title" required v-validate data-vv-as="title" v-model="politician.title">
		                    <div class="invalid-feedback">
		                        {{errors.first('politician_title')}}
		                        {{(backend_errors != null) ? (backend_errors.title != null) ? backend_errors.title[0] : null : null}}
		                    </div>
						</div>
						<div class="col-md-6">
							<label class="label">Constituency</label>
							<input type="text" class="form-control" name="politician_constituency" required v-validate data-vv-as="constituency" v-model="politician.constituency">
		                    <div class="invalid-feedback">
		                        {{errors.first('politician_constituency')}}
		                        {{(backend_errors != null) ? (backend_errors.constituency != null) ? backend_errors.constituency[0] : null : null}}
		                    </div>
						</div>
					</div>
					<div class="row row-space">
						<div class="col-md-6">
							<label class="label">City</label>
							<input type="text" class="form-control" name="city" v-model="politician.city">
						</div>
						<div class="col-md-6">
							<label class="label">Province</label>
							<input type="text" class="form-control" name="city" v-model="politician.province">
						</div>
					</div>
					<div class="row row-space">
						<div class="col-md-6">
							<label class="label">Email</label>
							<input type="email" class="form-control" name="email" required v-validate data-vv-as="email" v-model="politician.email">
		                    <div class="invalid-feedback">
		                        {{errors.first('email')}}
		                        {{(backend_errors != null) ? (backend_errors.email != null) ? backend_errors.email[0] : null : null}}
		                    </div>
						</div>
						<div class="col-md-6">
							<label class="label">Phone</label>
							<vue-phone-number v-model="politician.phone" default-country-code="CA" :no-country-selector="true" :required="true" @update="updatePhone"></vue-phone-number>
		                    <div class="invalid-feedback" v-if="politician.is_phone_valid == false">
		                        Phone number is invalid
		                    </div> 
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<input type="submit" value="Save" class="btn btn-primary">
				</div>
			</form>
		</modal-component>
    </div>
</template>

<script>
	import {mapGetters} from 'vuex';
	import {eventBus} from '../../app';
	
	export default {
		data() {
			return {
				file: null,
				search: '',
				fields: [
					{
						key: 'politician_name',
						sortable: true,
						label: 'Politician'
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
					}
				],
				politician: {
					firstname: null,
					lastname: null,
					email: null,
					phone: null,
					title: null,
					consituency: null,
					city: null,
					province: null,
					is_phone_valid: null
				},
				successful: null,
				error_message: null,
				backend_errors: null,
			}
		},
		computed: {
			...mapGetters([
				'getPoliticians',
				'getPoliticiansLength'
			])
		},
		created() {
			eventBus.$on('deletePolitician', (id) => {
				this.deletePolitician(id);
			});
		},
		methods: {
			handleFileUpload() {
				this.file = this.$refs.file.files[0];
			},
			updatePhone(e) {
                console.log(e.isValid);
                this.politician.is_phone_valid = e.isValid;
            },
			showModal() {
				$('#add-politician-modal').modal('show');
			},
			deletePolitician(id) {
				swal({
					title: "Are you sure?",
					text: "Once deleted, you will never be able to recover this politician!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((willDelete) => {
					if(willDelete) {
						axios.delete('/politician/' + id).then((response) => {
							swal(response.data.msg, {
								icon: 'success'
							});
							this.$store.dispatch('getPoliticians');
						}).catch((error) => {
							swal(error.response.data.msg, {
								icon: 'error'
							});
						});
					}
				});
			},
			updatePoliticianList() {
				this.$store.dispatch('getPoliticians');
			},
			submitPolitician() {
				this.$validator.validate().then(result => {
                    if(result) {
                    	if (this.politician.is_phone_valid == true) {
	                        axios.post('/politician', this.politician)
	                        .then((response) => {
	                            swal("Politician Added Successfully", {
									icon: "success"
								});
								this.$store.dispatch('getPoliticians');
								$('#add-politician-modal').modal('hide');
								this.resetData();
	                        }).catch((error) => {
	                            this.backend_errors = error.response.data.errors;
	                            swal(error.response.data.msg, {
									icon: 'error'
								});
	                        });
	                    }
                    }
                });
			},
			importPolitician() {
				let formData = new FormData();
				formData.append('file', this.file);
				axios.post('/politicians/import', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					}
				}).then((response) => {
					this.$store.dispatch('getPoliticians');
					swal("Politicians Are Being Added", {
						icon: "success"
					});
				}).catch((error) => {
					this.backend_errors = error.response.data.errors;
                        swal(error.response.data.msg, {
							icon: 'error'
						});
				})
			},
			resetData() {
                this.politician = {
					firstname: null,
					lastname: null,
					email: null,
					phone: null,
					title: null,
					consituency: null
				};
                this.$validator.pause();
                this.$nextTick(() => {
                    this.$validator.errors.clear();
                    this.$validator.fields.items.forEach(field => field.reset());
                    this.$validator.fields.items.forEach(field => this.errors.remove(field));
                    this.$validator.resume();
                });
            }
		}
	}
</script>