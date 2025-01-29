<template>
	<div class="card-body">
		<form @submit.prevent="submitChild" method="POST">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Child First Name (required)</label>
						<input type="text" class="form-control" name="firstname" required v-validate data-vv-as="child first name" v-model="child.firstname">
						<div class="invalid-feedback">
                    		{{errors.first('firstname')}}
                    		{{(backend_errors != null) ? (backend_errors.firstname != null) ? backend_errors.firstname[0] : null : null}}
                		</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Child Last Name (required)</label>
						<input type="text" class="form-control" name="lastname" required v-validate data-vv-as="child last name" v-model="child.lastname">
						<div class="invalid-feedback">
                    		{{errors.first('lastname')}}
                    		{{(backend_errors != null) ? (backend_errors.lastname != null) ? backend_errors.lastname[0] : null : null}}
                		</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="success-message" v-if="successful != null && successful == true">Child Update Successful</div>
            		<div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
					<button class="btn btn-primary pull-right save-btn">Update Child</button>
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
                child: {
                    firstname: null,
                    lastname: null
                },
                successful: null,
                error_message: null,
                backend_errors: null
			}
		},
		created() {
			axios.get('/child/edit/' + this.$route.params.child).then((response) => {
				this.child = response.data.child;
			}).catch((error) => {
				console.log(error);
			});
		},
		methods: {
			submitChild() {
				this.$validator.validate().then(result => {
                    if(result) {
						axios.patch('/child/' + this.child.id, this.child).then((response) => {
							console.log(response.data);
							if(response.data.success) {
								this.$router.push({path:'/children/' + response.data.url_slug});
								eventBus.$emit('refreshChild');
								this.successful = true;
								this.backend_errors = null;
								this.$store.dispatch('getChildren');
								this.$store.dispatch('getParents');
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
				});
			}
		}
	}
</script>