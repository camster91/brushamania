<template>
	<form @submit.prevent="updatePassword" method="POST">
		<br>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="label">Old Password</label>
					<input type="password" class="form-control" name="old_password" required v-validate v-model="user.old_password"  data-vv-as="old password">
					<div class="invalid-feedback">
                		{{errors.first('old_password')}}
          				<span v-if="!successful">{{error_message}}</span>
          				{{(backend_errors != null) ? (backend_errors.old_password != null) ? backend_errors.old_password[0] : null : null}}
            		</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="label">New Password</label>
					<input type="password" class="form-control" name="new_password" required v-validate="'min:8'" v-model="user.new_password" ref="new_password"  data-vv-as="new password">
					<div class="invalid-feedback">
                		{{errors.first('new_password')}}
                		{{(backend_errors != null) ? (backend_errors.new_password != null) ? backend_errors.new_password[0] : null : null}}
            		</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="label">Confirm New Password</label>
					<input type="password" class="form-control" name="confirm_new_password" required v-validate="'min:8|confirmed:new_password'" v-model="user.new_password_confirmation"  data-vv-as="new password confirmation">
					<div class="invalid-feedback">
                		{{errors.first('confirm_new_password')}}
            		</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="success-message" v-if="successful != null && successful == true">Password Updated Successfully</div>
        		<div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
				<button class="btn btn-primary pull-right save-btn">Update Password</button>
			</div>
		</div>
	</form>
</template>

<script>
	export default {
		data() {
			return {
				user: {
					old_password: null,
					new_password: null,
					new_password_confirmation: null
				},
				successful: null,
				error_message:  null,
				backend_errors: null
			}
		},
		methods: {
			updatePassword() {
				this.$validator.validate().then(result => {
                    if(result) {
                    	axios.post('/user/password', this.user).then((response) => {
							console.log(response);
							this.successful = true;
							this.resetData();
						}).catch((error) => {
							console.log('haha');
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
				
			},
			resetData() {
				this.backend_errors = null;
				this.user = {
					old_password: null,
					new_password: null,
					new_password_confirmation: null
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