<style scoped>
	#app-wrapper .displayBlock {
		display: block;
	}

	.modal-container {
		position: fixed; /* Stay in place */
		display: none;
		z-index: 9999; /* Sit on top */
		left: 0;
		top: 0;
		width: 100%; /* Full width */
		height: 100%; /* Full height */
		overflow: none; /* Enable scroll if needed */
		background-color: rgb(0,0,0); /* Fallback color */
		background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
	}
</style>

<template>
	<div class="modal-container" :class="{displayBlock : shouldShow}">
		<div class="modal" tabindex="-1" role="dialog" id="update-password" data-backdrop="static" data-keyboard="false" :data-show="true" :class="{displayBlock : shouldShow}">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Please Update Your Password</h5></span>
				        </button>
					</div>
					<form method="POST" @submit.prevent="updatePassword">
						<div class="modal-body">
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
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>	
	export default {
		data() {
			return {
				shouldShow: true,
				user: {
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
				axios.post('/user/password/initial', this.user)
					.then((response) => {
						console.log(response);
						swal("Password Updated Successfully", {
							icon: "success"
						});
						this.shouldShow = false;
					})
			}
		}
	}
</script>