<template>
	<form @submit.prevent="updateProfile" method="POST">
		<br>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="label">First Name</label>
					<input type="text" class="form-control" name="firstname" required v-validate v-model="user.contact_firstname" data-vv-as="first name">
					<div class="invalid-feedback">
                		{{errors.first('firstname')}}
                		{{(backend_errors != null) ? (backend_errors.contact_firstname != null) ? backend_errors.contact_firstname[0] : null : null}}
            		</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="label">Last Name</label>
					<input type="text" class="form-control" name="lastname" required v-validate v-model="user.contact_lastname" data-vv-as="last name">
					<div class="invalid-feedback">
                		{{errors.first('lastname')}}
                		{{(backend_errors != null) ? (backend_errors.contact_lastname != null) ? backend_errors.contact_lastname[0] : null : null}}
            		</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="label">Email</label>
					<input type="email" class="form-control" name="email" required v-validate v-model="user.email">
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
				user: {
					contact_firstname: '',
					contact_lastname: '',
					email: '',
					id: ''
				},
				successful: null,
				error_message: null,
				backend_errors: null
			}
		},
		created() {
			this.getProfile();
		},
		methods: {
			getProfile() {
				axios.get('/user/profile').then((response) => {
					this.user.contact_firstname = response.data.firstname;
					this.user.contact_lastname = response.data.lastname;
					this.user.email = response.data.email;
					this.user.id = response.data.id;
					if(response.data.contact_salutation != null) {
						this.user.contact_salutation = response.data.contact_salutation;
					}
				}).catch((error) => {
					console.log(error);
				});
			},
			updateProfile() {
				this.$validator.validate().then(result => {
                    if(result) {
                    	axios.patch('/user/profile/' + this.user.id, this.user).then((response) => {
							console.log(response.data);
							this.successful = true;
							this.backend_errors = null;
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
                });
				
			}
		}
	}
</script>