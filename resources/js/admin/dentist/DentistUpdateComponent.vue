<template>
	<div class="card-body">
		<form @submit.prevent="submitDentist" method="POST">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label class="label">Clinic Name</label>
						<input type="text" class="form-control" name="clinic_name" required v-validate v-model="dentist.clinic_name" data-vv-as="clinic name">
						<div class="invalid-feedback">
                    		{{errors.first('clinic_name')}}
                		</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Dentist First Name (required)</label>
						<input type="text" class="form-control" name="dentist_firstname" required v-validate v-model="dentist.dentist_firstname" data-vv-as="dentist firstname">
						<div class="invalid-feedback">
                    		{{errors.first('dentist_firstname')}}
                    		{{(backend_errors != null) ? (backend_errors.dentist_firstname != null) ? backend_errors.dentist_firstname[0] : null : null}}
                		</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Dentist Last Name (required)</label>
						<input type="text" class="form-control" name="dentist_lastname" required v-validate v-model="dentist.dentist_lastname" data-vv-as="dentist lastname">
						<div class="invalid-feedback">
                    		{{errors.first('dentist_lastname')}}
                    		{{(backend_errors != null) ? (backend_errors.dentist_lastname != null) ? backend_errors.dentist_lastname[0] : null : null}}
                		</div>
					</div>
				</div>
			</div>
			<div class="row" v-if="role != null && role == 'admin'">
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
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Dentist Contact Salutation (required)</label>
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
			</div>
			<div class="row" v-if="role != null && role == 'admin'">
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Contact First Name (required)</label>
						<input type="text" class="form-control" name="contact_firstname" required v-validate v-model="dentist.contact_firstname" data-vv-as="contact first name">
						<div class="invalid-feedback">
                    		{{errors.first('contact_firstname')}}
                    		{{(backend_errors != null) ? (backend_errors.contact_firstname != null) ? backend_errors.contact_firstname[0] : null : null}}
                		</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Contact Last Name (required)</label>
						<input type="text" class="form-control" name="contact_firstname" required v-validate v-model="dentist.contact_lastname" data-vv-as="contact last name">
						<div class="invalid-feedback">
                    		{{errors.first('contact_lastname')}}
                    		{{(backend_errors != null) ? (backend_errors.contact_lastname != null) ? backend_errors.contact_lastname[0] : null : null}}
                		</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Address</label>
						<input type="text" class="form-control" name="address" v-model="dentist.address">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">City</label>
						<input type="text" class="form-control" name="city" v-model="dentist.city">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Province</label>
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
						<label class="label">Postal Code</label>
						<input type="text" class="form-control" name="postal_code" v-model="dentist.postal_code">
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
						<div class="invalid-feedback">
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
					<div class="success-message" v-if="successful != null && successful == true">Dentist Updated Successfully</div>
            		<div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
					<button class="btn btn-primary pull-right save-btn">Update Dentist</button>
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
                dentist: {
                    clinic_name: null,
                    dentist_firstname: null,
                    dentist_lastname: null,
                    email: null,
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
                    add_clinic_to_dentistfind: false,
                    is_organizing_committee: false,
                    is_lootbag_committee: false,
                    is_pr_media_committee: false,
                    is_sponsor_committee: false,
                    is_telephoning_committee: false,
                    postcard: 0,
                    dental_society_name: null
                },
                successful: null,
                error_message: null,
                backend_errors: null
			}
		},
		props: {
			role: String
		},
		created() {
			// console.log(this.$route.params.dentist);
			axios.get('/dentist/edit/' + this.$route.params.dentist).then((response) => {
				this.dentist = response.data.dentist;
				this.dentist.is_phone_valid = true;
			}).catch((error) => {
				console.log(error);
			});
		},
		methods: {
			updatePhone(e) {
                console.log(e.isValid);
                this.dentist.is_phone_valid = e.isValid;
            },
			submitDentist() {
				this.$validator.validate().then(result => {
                    if(result) {
                    	if (this.dentist.is_phone_valid == true) {
	                    	axios.patch('/dentist/' + this.dentist.id, this.dentist).then((response) => {
								console.log(response.data);
								if(response.data.success) {
									this.$router.push({path:'/dentists/' + response.data.url_slug});
									eventBus.$emit('refreshDentist');
									this.successful = true;
									this.backend_errors = null;
									this.$store.dispatch('getDentists');
								} else {
									this.successful = false;
									this.error_message = response.data.msg;
								}
							}).catch((error) => {
								console.log(error);
								this.successful = false;
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