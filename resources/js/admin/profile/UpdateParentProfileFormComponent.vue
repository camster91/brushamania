<template>
	<form @submit.prevent="submitParent" method="POST">
		<br>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="label">Parent First Name (required)</label>
					<input type="text" class="form-control" name="parent_firstname" required v-validate data-vv-as="parent first name" v-model="parent.contact_firstname">
					<div class="invalid-feedback">
                		{{errors.first('parent_firstname')}}
                		{{(backend_errors != null) ? (backend_errors.contact_firstname != null) ? backend_errors.contact_firstname[0] : null : null}}
            		</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="label">Parent Last Name (required)</label>
					<input type="text" class="form-control" name="parent_lastname" required v-validate data-vv-as="parent last name" v-model="parent.contact_lastname">
					<div class="invalid-feedback">
                		{{errors.first('parent_lastname')}}
                		{{(backend_errors != null) ? (backend_errors.contact_lastname != null) ? backend_errors.contact_lastname[0] : null : null}}
            		</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="label">Phone</label>
					<vue-phone-number v-model="parent.phone" default-country-code="CA" :no-country-selector="true" :required="true" @update="updatePhone"></vue-phone-number>
                    <div class="invalid-feedback" v-if="parent.is_phone_valid == false">
                        Phone number is invalid
                    </div> 
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="label">Email (required)</label>
					<input type="email" class="form-control" name="email" required v-validate v-model="parent.email">
					<div class="invalid-feedback">
                		{{errors.first('email')}}
                		{{(backend_errors != null) ? (backend_errors.email != null) ? backend_errors.email[0] : null : null}}
            		</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="success-message" v-if="successful != null && successful == true">Profile Updated Successfully</div>
        		<div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
				<button class="btn btn-primary pull-right save-btn">Update Profile</button>
			</div>
		</div>
	</form>
</template>

<script>
	import {eventBus} from '../../app';

	export default {
		data() {
			return {
                parent: {
                	id: null,
                    contact_firstname: null,
                    contact_lastname: null,
                    phone: null,
                    is_phone_valid: true,
                    email: null
                },
                successful: null,
                error_message: null,
                backend_errors: null
			}
		},
		created() {
			axios.get('/user/url-slug').then((response) => {
				axios.get('/parent/edit/' + response.data.url_slug).then((response) => {
					this.parent = response.data.parent;
					this.parent.is_phone_valid = true;
				}).catch((error) => {
					console.log(error);
				});
			}).catch((error) => {
				console.log(error);
			});
			
		},
		methods: {
			submitParent() {
				this.$validator.validate().then(result => {
                    if(result) {
                    	if (this.parent.is_phone_valid == true) {
							axios.patch('/parent/' + this.parent.id, this.parent).then((response) => {
								console.log(response.data);
								if(response.data.success) {
									this.successful = true;
									this.backend_errors = null;
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