<template>
	<div class="main-panel">
		<header-title-component page_title="School">
        </header-title-component>
        <div class="content">
        	<div class="row">
        		<div class="col-md-12">
        			<form @submit.prevent="submitSchool" method="POST">
        				<div class="card">
        					<div class="card-header">
        						<div class="row">
        							<div class="col-md-6">
        								<h4 class="card-title">Add New School</h4>
        							</div>s
        						</div>
        					</div>
        					<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">School Name (required)</label>
											<input type="text" class="form-control" name="name" required v-validate v-model="school.name" data-vv-as="school name">
											<div class="invalid-feedback">
					                    		{{errors.first('name')}}
					                    		{{(backend_errors != null) ? (backend_errors.name != null) ? backend_errors.name[0] : null : null}}
					                		</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Address (required)</label>
											<input type="text" class="form-control" name="address" required v-validate v-model="school.address">
											<div class="invalid-feedback">
					                    		{{errors.first('address')}}
					                    		{{(backend_errors != null) ? (backend_errors.address != null) ? backend_errors.address[0] : null : null}}
					                		</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">City (required)</label>
											<input type="text" class="form-control" name="city" required v-validate v-model="school.city">
											<div class="invalid-feedback">
					                    		{{errors.first('city')}}
					                    		{{(backend_errors != null) ? (backend_errors.city != null) ? backend_errors.city[0] : null : null}}
					                		</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Province (required)</label>
											<select name="province" class="form-control" v-model="school.province" required v-validate>
						                        <option value="AB">Alberta</option>
						                        <option value="BC">British Columbia</option>
						                        <option value="MB">Manitoba</option>
						                        <option value="NB">New Brunswick</option>
						                        <option value="NL">Newfoundland and Labrador</option>
						                        <option value="NS">Nova Scotia</option>
						                        <option value="ON">Ontario</option>
						                        <option value="PE">Prince Edward Island</option>
						                        <option value="QC">Quebec</option>
						                        <option value="SK">Saskatchewan</option>
						                        <option value="NT">Northwest Territories</option>
						                        <option value="NU">Nunavut</option>
						                        <option value="YT">Yukon</option>
						                    </select>
						                    <div class="invalid-feedback">
					                    		{{errors.first('province')}}
					                    		{{(backend_errors != null) ? (backend_errors.province != null) ? backend_errors.province[0] : null : null}}
					                		</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Postal Code (required)</label>
											<input type="text" class="form-control" name="postal_code" required v-validate v-model="school.postal_code" data-vv-as="postal code">
											<div class="invalid-feedback">
					                    		{{errors.first('postal_code')}}
					                    		{{(backend_errors != null) ? (backend_errors.postal_code != null) ? backend_errors.postal_code[0] : null : null}}
					                		</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Phone (required)</label>
					                		<vue-phone-number v-model="school.phone" default-country-code="CA" :no-country-selector="true" :required="true" @update="updatePhone"></vue-phone-number>
						                    <div class="invalid-feedback" v-if="school.is_phone_valid == false">
						                        Phone number is invalid
						                    </div> 
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="success-message" v-if="successful != null && successful == true">School Registered Successfully</div>
					            		<div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
										<button class="btn btn-primary pull-right save-btn">Register School</button>
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
                school: {
                    name: null,
                    address: null,
                    city: null,
                    province: 'ON',
                    postal_code: null,
                    phone: null,
                    is_phone_valid: null,
                    contact_salutation: 'Ms.',
                    contact_firstname: null,
                    contact_lastname: null,
                    email: null,
                    email_confirmation: null,
                },
                successful: null,
                error_message: null,
                backend_errors: null
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
			updatePhone(e) {
                console.log(e.isValid);
                this.school.is_phone_valid = e.isValid;
            },
			submitSchool() {
				this.school.email_confirmation = this.school.email;
				this.$validator.validate().then(result => {
	                if(result) {
	                    if (this.school.is_phone_valid == true) {
	                        axios.post('/school-unregistered', this.school)
	                        .then((response) => {
	                            console.log(response.data);
	                            this.successful = true;
	                            console.log(this.successful);
	                            this.resetData();
	                            console.log(this.successful);
	                            this.$store.dispatch('getSchools', this.getActiveYear);
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
			resetData() {
                this.school = {
                    name: null,
                    address: null,
                    city: null,
                    province: 'Ontario',
                    postal_code: null,
                    phone: null,
                    is_phone_valid: null,
                    contact_salutation: 'Ms.',
                    contact_firstname: null,
                    contact_lastname: null,
                    email: null,
                    email_confirmation: null,
                };
                this.$validator.pause();
                this.$nextTick(() => {
                    this.$validator.errors.clear();
                    this.$validator.fields.items.forEach(field => field.reset());
                    this.$validator.fields.items.forEach(field => this.errors.remove(field));
                    this.$validator.resume();
                });
                console.log(this.successful);
            }
		}
	}
</script>