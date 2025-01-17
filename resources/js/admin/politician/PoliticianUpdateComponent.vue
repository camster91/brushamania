<template>
	<div class="card-body">
		<form @submit.prevent="submitPolitician" method="POST">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">First Name (required)</label>
						<input type="text" class="form-control" name="firstname" required v-validate data-vv-as="first name" v-model="politician.firstname">
						<div class="invalid-feedback">
                    		{{errors.first('firstname')}}
                    		{{(backend_errors != null) ? (backend_errors.firstname != null) ? backend_errors.firstname[0] : null : null}}
                		</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Last Name (required)</label>
						<input type="text" class="form-control" name="lastname" required v-validate data-vv-as="last name" v-model="politician.lastname">
						<div class="invalid-feedback">
                    		{{errors.first('lastname')}}
                    		{{(backend_errors != null) ? (backend_errors.lastname != null) ? backend_errors.lastname[0] : null : null}}
                		</div>
					</div>
				</div>
			</div>
			<div class="row">
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
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">City</label>
						<input type="text" class="form-control" name="city" v-model="politician.city">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Province</label>
						<input type="text" class="form-control" name="city" v-model="politician.province">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Phone</label>
						<vue-phone-number v-model="politician.phone" default-country-code="CA" :no-country-selector="true" :required="true" @update="updatePhone"></vue-phone-number>
	                    <div class="invalid-feedback" v-if="politician.is_phone_valid == false">
	                        Phone number is invalid
	                    </div> 
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Email (required)</label>
						<input type="email" class="form-control" name="email" required v-validate v-model="politician.email">
						<div class="invalid-feedback">
                    		{{errors.first('email')}}
                    		{{(backend_errors != null) ? (backend_errors.email != null) ? backend_errors.email[0] : null : null}}
                		</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="success-message" v-if="successful != null && successful == true">Politician Updated Successfully</div>
            		<div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
					<button class="btn btn-primary pull-right save-btn">Update Politician</button>
				</div>
			</div>
		</form>
	</div>
</template>

<script>
	import {eventBus} from '../../app';

	export default {
		data() {
			return {
                politician: {
                	id: null,
                    firstname: null,
                    lastname: null,
                    phone: null,
                    title: null,
					constituency: null,
					city: null,
					province: null,
                    is_phone_valid: true,
                    email: null
                },
                successful: null,
                error_message: null,
                backend_errors: null
			}
		},
		created() {
			axios.get('/politician/edit/' + this.$route.params.politician).then((response) => {
				this.politician = response.data.politician;
				this.politician.is_phone_valid = true;
			}).catch((error) => {
				console.log(error);
			});
		},
		methods: {
			updatePhone(e) {
                console.log(e.isValid);
                this.politician.is_phone_valid = e.isValid;
            },
			submitPolitician() {
				this.$validator.validate().then(result => {
                    if(result) {
                    	if (this.politician.is_phone_valid == true) {
							axios.patch('/politician/' + this.politician.id, this.politician).then((response) => {
								console.log(response.data);
								if(response.data.success) {
									this.$router.push({path:'/politicians/' + response.data.url_slug});
									eventBus.$emit('refreshPolitician');
									this.successful = true;
									this.backend_errors = null;
									this.$store.dispatch('getPoliticians');
								} else {
									this.successful = false;
									this.error_message = response.data.msg;
								}
							}).catch((error) => {
								console.log(error);
								this.successful = false;
								this.error_message = error.response.data.msg;
								if(error.response.data.msg != null) {
									this.error_message = error.response.data.msg;
								} else {
									this.error_message = error.response.data.message;
								}
								this.backend_errors = error.response.data.errors;
							});
						}
					}
				});
			}
		}
	}
</script>