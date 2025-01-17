<template>
	<div class="main-panel">
		<header-title-component page_title="Dentist">
        </header-title-component>
        <div class="content">
        	<div class="row">
        		<div class="col-md-12">
        			<form @submit.prevent="submitDentist" method="POST">
        				<div class="card">
        					<div class="card-header">
        						<div class="row">
        							<div class="col-md-6">
        								<h4 class="card-title">Add New Dentist</h4>
        							</div>
        							<div class="col-md-6">
        								<div class="form-group">
											<label class="label">Year Registered</label>
											<select name="year" class="form-control" v-model="dentist.selected_year">
								        		<option v-for="year in getYears" :value="year">{{year}}</option>
								        	</select>
										</div>
        							</div>
        						</div>
        					</div>
        					<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="label">Clinic Name</label>
											<input type="text" class="form-control" name="clinic_name" required v-validate v-model="dentist.clinic_name" data-vv-as="clinic name">
											<div class="invalid-feedback">
					                    		{{errors.first('clinic_name')}}
					                    		{{(backend_errors != null) ? (backend_errors.clinic_name != null) ? backend_errors.clinic_name[0] : null : null}}
					                		</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Dentist First Name (required)</label>
											<input type="text" class="form-control" name="dentist_firstname" required v-validate v-model="dentist.dentist_firstname" data-vv-as="dentist first name">
											<div class="invalid-feedback">
					                    		{{errors.first('dentist_firstname')}}
					                    		{{(backend_errors != null) ? (backend_errors.dentist_firstname != null) ? backend_errors.dentist_firstname[0] : null : null}}
					                		</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Dentist Last Name (required)</label>
											<input type="text" class="form-control" name="dentist_lastname" required v-validate v-model="dentist.dentist_lastname" data-vv-as="dentist last name">
											<div class="invalid-feedback">
					                    		{{errors.first('dentist_lastname')}}
					                    		{{(backend_errors != null) ? (backend_errors.dentist_lastname != null) ? backend_errors.dentist_lastname[0] : null : null}}
					                		</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Contact Salutation (required)</label>
											<select name="contact_salutation" class="form-control" v-model="dentist.contact_salutation" required v-validate>
						                        <option value="Dr.">Dr</option>
						                        <option value="Ms.">Ms</option>
						                        <option value="Miss">Miss</option>
						                        <option value="Mrs.">Mrs</option>
						                        <option value="Mr.">Mr</option>
						                    </select>
						                    <div class="invalid-feedback">
					                    		{{errors.first('contact_salutation')}}
					                    		{{(backend_errors != null) ? (backend_errors.contact_salutation != null) ? backend_errors.contact_salutation[0] : null : null}}
					                		</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Contact First Name</label>
											<input type="text" class="form-control" name="contact_firstname" v-model="dentist.contact_firstname">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Contact Last Name</label>
											<input type="text" class="form-control" name="contact_lastname" v-model="dentist.contact_lastname">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Email (required)</label>
											<input type="email" class="form-control" name="email" required v-validate v-model="dentist.email">
											<div class="invalid-feedback">
					                    		{{errors.first('email')}}
					                    		{{(backend_errors != null) ? (backend_errors.email != null) ? backend_errors.email[0] : null : null}}
					                		</div>
										</div>
									</div>				
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Address (required)</label>
											<input type="text" class="form-control" name="address" required v-validate v-model="dentist.address">
											<div class="invalid-feedback">
					                    		{{errors.first('address')}}
					                    		{{(backend_errors != null) ? (backend_errors.address != null) ? backend_errors.address[0] : null : null}}
					                		</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">City (required)</label>
											<input type="text" class="form-control" name="city" required v-validate v-model="dentist.city">
											<div class="invalid-feedback">
					                    		{{errors.first('city')}}
					                    		{{(backend_errors != null) ? (backend_errors.city != null) ? backend_errors.city[0] : null : null}}
					                		</div>
										</div>
									</div>				
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Province (required)</label>
											<select name="province" class="form-control" v-model="dentist.province">
												<option value="0">Select a Province</option>
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
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Postal Code (required)</label>
											<input type="text" class="form-control" name="postal_code" required v-validate v-model="dentist.postal_code" data-vv-as="postal code">
											<div class="invalid-feedback">
					                    		{{errors.first('postal_code')}}
					                    		{{(backend_errors != null) ? (backend_errors.postal_code != null) ? backend_errors.postal_code[0] : null : null}}
					                		</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Phone (required)</label>
					                		<vue-phone-number v-model="dentist.phone" default-country-code="CA" :no-country-selector="true" :required="true" @update="updatePhone"></vue-phone-number>
						                    <div class="invalid-feedback" v-if="dentist.is_phone_valid == false">
						                        Phone number is invalid
						                    </div> 
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Website</label>
					                		<input type="text" class="form-control" name="website" v-validate="'url'" v-model="dentist.website">
						                    <div class="invalid-input">
						                        {{errors.first('website')}}
						                    </div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="label">Dental Society</label>
											<input type="text" class="form-control" name="dental_society_name" v-model="dentist.dental_society_name">
										</div>
									</div>
								</div>
					            <div class="row row-space">
					            	<div class="col-md-12">
					            		<div class="checkbox">
						                    <label class="control control--checkbox add-to-df">Add Clinic to <a href="https://dentistfind.com">DentistFind.com</a>
						                        <input type="checkbox" v-model="dentist.add_clinic_to_dentistfind" />
						                        <div class="control__indicator"></div>
						                    </label>
						                </div>
						                <div class="invalid-feedback">
					                    	{{(backend_errors != null) ? (backend_errors.add_clinic_to_dentistfind != null) ? backend_errors.add_clinic_to_dentistfind[0] : null : null}}
					                	</div>
					            	</div>
					            </div>
					            <div class="row row-space">
					            	<div class="col-md-12">
						                <label class="label">Brush-a-mania Postcards</label>
						                <div class="post-card-container">
						                    <div class="radio">
						                        <label class="control control--radio">0
						                            <input type="radio" name="postcard" v-model="dentist.postcard" :value="0" />
						                            <div class="control__indicator"></div>
						                        </label>
						                    </div>
						                    <div class="radio">
						                        <label class="control control--radio">50
						                            <input type="radio" name="postcard" v-model="dentist.postcard" :value="50" />
						                            <div class="control__indicator"></div>
						                        </label>
						                    </div>
						                    <div class="radio">
						                        <label class="control control--radio">100
						                            <input type="radio" name="postcard" v-model="dentist.postcard" :value="100" />
						                            <div class="control__indicator"></div>
						                        </label>
						                    </div>
						                    <div class="radio">
						                        <label class="control control--radio">150
						                            <input type="radio" name="postcard" v-model="dentist.postcard" :value="150" />
						                            <div class="control__indicator"></div>
						                        </label>
						                    </div>
						                    <div class="radio">
						                        <label class="control control--radio">200
						                            <input type="radio" name="postcard" v-model="dentist.postcard" :value="200" />
						                            <div class="control__indicator"></div>
						                        </label>
						                    </div>
						                </div>	
						                <div class="invalid-feedback">
					                    	{{(backend_errors != null) ? (backend_errors.postcard != null) ? backend_errors.postcard[0] : null : null}}
					                	</div>
					            	</div>
					            </div>
								<div class="row row-space" style="margin-top: 20px; margin-bottom: 20px;">
									<div class="col-md-12">
										<label class="label">Helping Committees</label>
									</div>
								</div>
								<div class="row row-space">
									<div class="col-md-12">
										<label class="label">Organizing Committee</label>
						                <div class="checkbox">
						                    <label class="control control--checkbox">Oversee event organization.
						                        <input type="checkbox" v-model="dentist.is_organizing_committee" />
						                        <div class="control__indicator"></div>
						                    </label>
						                </div>
						                <div class="invalid-feedback">
					                    	{{(backend_errors != null) ? (backend_errors.is_organizing_committee != null) ? backend_errors.is_organizing_committee[0] : null : null}}
					                	</div>
									</div>
					            </div>
					            <div class="row row-space">
					            	<div class="col-md-12">
						                <label class="label">Loot Bag Committee</label>
						                <div class="checkbox">
						                    <label class="control control--checkbox">Collect contents of loot bags, assemble and distribute to schools.
						                        <input type="checkbox" v-model="dentist.is_lootbag_committee" />
						                        <div class="control__indicator"></div>
						                    </label>
						                </div>
						                <div class="invalid-feedback">
					                    	{{(backend_errors != null) ? (backend_errors.is_lootbag_committee != null) ? backend_errors.is_lootbag_committee[0] : null : null}}
					                	</div>            		
					            	</div>
					            </div>
					            <div class="row row-space">
					            	<div class="col-md-12">
						                <label class="label">PR Media Committee</label>
						                <div class="checkbox">
						                    <label class="control control--checkbox">Prepare News Releases, Invite media to event, Invite politicians and guests; Arrange interviews; Arrange Public Service Announcements; Promote Brush-a-mania throughout Canada. (Place posters in libraries, rec centres, etc.)
						                        <input type="checkbox" v-model="dentist.is_pr_media_committee" />
						                        <div class="control__indicator"></div>
						                    </label>
						                </div> 
						                <div class="invalid-feedback">
					                    	{{(backend_errors != null) ? (backend_errors.is_pr_media_committee != null) ? backend_errors.is_pr_media_committee[0] : null : null}}
					                	</div>           		
					            	</div>
					            </div>
					            <div class="row row-space">
					            	<div class="col-md-12">
					            		<label class="label">Sponsor Committee</label>
						                <div class="checkbox">
						                    <label class="control control--checkbox">Contact Sponsors for money, prizes, product or services.
						                        <input type="checkbox" v-model="dentist.is_sponsor_committee" />
						                        <div class="control__indicator"></div>
						                    </label>
						                </div>
						                <div class="invalid-feedback">
					                    	{{(backend_errors != null) ? (backend_errors.is_sponsor_committee != null) ? backend_errors.is_sponsor_committee[0] : null : null}}
					                	</div>  
					            	</div>
					            </div>
					            <div class="row row-space">
					            	<div class="col-md-12">
					            		<label class="label">Telephoning Committee</label>
					                    <div class="checkbox">
						                    <label class="control control--checkbox">Recruit/follow-up with schools, dentists, Rotarians.
						                        <input type="checkbox" v-model="dentist.is_telephoning_committee" />
						                        <div class="control__indicator"></div>
						                    </label>
						                </div>
						                <div class="invalid-feedback">
					                    	{{(backend_errors != null) ? (backend_errors.is_telephoning_committee != null) ? backend_errors.is_telephoning_committee[0] : null : null}}
					                	</div>  
					            	</div>
					            </div>
								<div class="row">
									<div class="col-md-12">
										<div class="success-message" v-if="successful != null && successful == true">Dentist Registered Successfully</div>
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
                dentist: {
                    clinic_name: null,
                    dentist_firstname: null,
                    dentist_lastname: null,
                    email: null,
                    email_confirmation: null,
                    contact_salutation: 'Dr.',
                    contact_firstname: null,
                    contact_lastname: null,
                    address: null,
                    city: null,
                    province: 0,
                    postal_code: null,
                    phone: null,
                    is_phone_valid: true,
                    website: null,
                    will_enter_school: null,
                    add_clinic_to_dentistfind: false,
                    is_organizing_committee: false,
                    is_lootbag_committee: false,
                    is_pr_media_committee: false,
                    is_sponsor_committee: false,
                    is_telephoning_committee: false,
                    postcard: 0,
                    will_pick_up_local: false,
                    dental_society_name: null,
                    selected_year: null
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
                this.dentist.is_phone_valid = e.isValid;
            },
			submitDentist() {
				this.dentist.email_confirmation = this.dentist.email;
				this.$validator.validate().then(result => {
	                if(result) {
	                    if (this.dentist.is_phone_valid == true) {
	                    	if ((this.dentist.dentist_firstname == null || this.dentist.dentist_lastname == "") && (this.dentist.dentist_lastname == null || this.dentist.dentist_lastname == "")) {
                                this.dentist.contact_firstname = this.dentist.dentist_firstname;
                                this.dentist.contact_lastname = this.dentist.dentist_lastname;
                            }
	                        axios.post('/dentist', this.dentist)
	                        .then((response) => {
	                            console.log(response.data);
	                            this.successful = true;
	                            console.log(this.successful);
	                            this.resetData();
	                            console.log(this.successful);
	                            this.$store.dispatch('getDentists', this.getActiveYear);
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
			setYear() {
				axios.get('/year/active').then((response) => {
					this.dentist.selected_year = response.data.year;
				}).catch((error) => {
					console.log(error);
				})
			},
			resetData() {
                this.dentist = {
                    clinic_name: null,
                    dentist_firstname: null,
                    dentist_lastname: null,
                    email: null,
                    email_confirmation: null,
                    contact_salutation: 'Dr.',
                    contact_firstname: null,
                    contact_lastname: null,
                    address: null,
                    city: null,
                    province: 0,
                    postal_code: null,
                    phone: null,
                    is_phone_valid: true,
                    website: null,
                    will_enter_school: null,
                    add_clinic_to_dentistfind: false,
                    is_organizing_committee: false,
                    is_lootbag_committee: false,
                    is_pr_media_committee: false,
                    is_sponsor_committee: false,
                    is_telephoning_committee: false,
                    postcard: 0,
                    will_pick_up_local: false,
                    dental_society_name: null,
                    selected_year: this.getActiveYear
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