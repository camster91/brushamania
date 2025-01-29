<template>
	<div class="main-panel">
		<header-title-component page_title="Parent and Children">
        </header-title-component>
        <div class="content">
        	<div class="row">
        		<div class="col-md-12">
        			<form @submit.prevent="submitParent" method="POST">
        				<div class="card">
        					<div class="card-header">
        						<div class="row">
        							<div class="col-md-6">
        								<h4 class="card-title">Add New Parent and Children</h4>
        							</div>
        							<div class="col-md-6">
        								<div class="form-group">
											<label class="label">Year Registered</label>
											<select name="year" class="form-control" v-model="parent.selected_year">
								        		<option v-for="year in getYears" :value="year" :key="year">{{year}}</option>
								        	</select>
										</div>
        							</div>
        						</div>
        					</div>
        					<div class="card-body">
        						<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">First Name (required)</label>
											<input type="text" class="form-control" name="parent_firstname" required v-validate data-vv-as="first name" v-model="parent.contact_firstname">
											<div class="invalid-feedback">
					                    		{{errors.first('parent_firstname')}}
					                    		{{(backend_errors != null) ? (backend_errors.contact_firstname != null) ? backend_errors.contact_firstname[0] : null : null}}
					                		</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Last Name (required)</label>
											<input type="text" class="form-control" name="parent_lastname" required v-validate data-vv-as="last name" v-model="parent.contact_lastname">
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
								<div class="row row-space" style="margin-top: 20px; margin-bottom: 20px;">
									<div class="col-md-12">
										<label class="label">Children</label>
									</div>
								</div>
        						<div class="row" v-for="(child, index) in parent.children" :key="index">
        							<div class="col-md-5">
        								<div class="form-group">
											<label class="label">Child First Name (required)</label>
											<input type="text" class="form-control" name="firstname" required v-validate data-vv-as="child first name" v-model="child.firstname">
											<div class="invalid-feedback">
					                    		{{errors.first('firstname' + index)}}
					                		</div>
										</div>
        							</div>
        							<div class="col-md-5">
        								<div class="form-group">
											<label class="label">Child Last Name (required)</label>
											<input type="text" class="form-control" name="lastname" required v-validate data-vv-as="child last name" v-model="child.lastname">
											<div class="invalid-feedback">
					                    		{{errors.first('lastname' + index)}}
					                		</div>
										</div>
        							</div>
        							<div class="col-md-2 btn-container">
					            		<button type="button" class="btn btn-circle add-btn pull-right" @click="addChild()"><i class="fa fa-plus" ></i></button>
					                	<button type="button" class="btn btn-circle remove-btn pull-right" @click="removeChild(index)"><i class="fa fa-minus" ></i></button>
					            	</div>
        						</div>
        						<div class="row">
									<div class="col-md-12">
										<div class="success-message" v-if="successful != null && successful == true">Parent Registered Successfully</div>
					            		<div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
										<button class="btn btn-primary pull-right save-btn">Register Parent</button>
									</div>
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
	import {mapGetters} from 'vuex';

	export default {
		data() {
			return {
				parent: {
					contact_firstname: null,
					contact_lastname: null,
					email: null,
					email_confirmation: null,
					phone: null,
                    is_phone_valid: null,
					children: [{
						firstname: null,
						lastname: null
					}],
                    selected_year: null
				},
				successful: null,
                error_message: null,
                backend_errors: null,
			}
		},
		computed: {
			...mapGetters([
				'getYears',
				'getActiveYear'
			])
		},
		created() {
			this.setYear();
		},
		methods: {
			setYear() {
				axios.get('/year/active').then((response) => {
					this.parent.selected_year = response.data.year;
				}).catch((error) => {
					console.log(error);
				})
			},
            submitParent() {
            	this.parent.email_confirmation = this.parent.email;
                this.$validator.validate().then(result => {
                    if(result) {
                        if (this.parent.is_phone_valid == true) {
                            axios.post('/parent', this.parent)
                            .then((response) => {
                                console.log(response.data);
	                            this.successful = true;
	                            console.log(this.successful);
	                            this.resetData();
	                            console.log(this.successful);
	                            this.$store.dispatch('getParents', this.getActiveYear);
	                            this.$store.dispatch('getChildren', this.getActiveYear);
                            }).catch((error) => {
                                this.successful = false;
	                            console.log(this.successful);
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
            },
            updatePhone(e) {
                console.log(e.isValid);
                this.parent.is_phone_valid = e.isValid;
            },
            addChild() {
            	this.parent.children.push({
            		firstname: null,
            		lastname: null
            	});
            },
            removeChild(id) {
				if(this.parent.children.length > 1) {
					this.parent.children.splice(id, 1);
				}
            },
            resetData() {
                this.parent = {
                    contact_firstname: null,
                    contact_lastname: null,
                    email: null,
                    email_confirmation: null,
                    phone: null,
                    is_phone_valid: null,
                    children: [{
                        firstname: null,
                        lastname: null
                    }],
                    gRecaptchaResponse: null
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