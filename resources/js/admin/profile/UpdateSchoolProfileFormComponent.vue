<template>
	<form @submit.prevent="submitSchool" method="POST">
		<br>
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
			<div class="col-md-6">
				<div class="form-group">
					<label class="label">School Email (required)</label>
					<input type="email" class="form-control" name="email" required v-validate v-model="school.email">
					<div class="invalid-feedback">
                		{{errors.first('email')}}
                		{{(backend_errors != null) ? (backend_errors.email != null) ? backend_errors.email[0] : null : null}}
            		</div>
				</div>
			</div>				
			<div class="col-md-6">
				<div class="form-group">
					<label class="label">School Contact Salutation (required)</label>
					<select name="contact_salutation" class="form-control" v-model="school.contact_salutation" required v-validate>
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
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="label">School Contact First Name (required)</label>
					<input type="text" class="form-control" name="contact_firstname" required v-validate v-model="school.contact_firstname" data-vv-as="school contact first name">
					<div class="invalid-feedback">
                		{{errors.first('contact_firstname')}}
                		{{(backend_errors != null) ? (backend_errors.contact_firstname != null) ? backend_errors.contact_firstname[0] : null : null}}
            		</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="label">School Contact Last Name (required)</label>
					<input type="text" class="form-control" name="contact_lastname" required v-validate v-model="school.contact_lastname" data-vv-as="school contact last name">
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
					<label class="label">Number of Classes</label>
					<input type="text" class="form-control" name="number_of_classes" required v-validate="'numeric|min_value:1'" data-vv-as="total number of classes" v-model="school.number_of_classes">
					<div class="invalid-feedback">
                		{{errors.first('number_of_classes')}}
                		{{(backend_errors != null) ? (backend_errors.number_of_classes != null) ? backend_errors.number_of_classes[0] : null : null}}
            		</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="label">Number of Students</label>
					<input type="text" class="form-control" name="number_of_students" required v-validate="'numeric|min_value:1'" data-vv-as="total number of students" v-model="school.number_of_students">
					<div class="invalid-feedback">
                		{{errors.first('number_of_students')}}
                		{{(backend_errors != null) ? (backend_errors.number_of_students != null) ? backend_errors.number_of_students[0] : null : null}}
            		</div>
				</div>
			</div>
		</div>			
		<div class="row">
			<div class="col-md-12">
				<div class="success-message" v-if="successful != null && successful == true">Profile Successfully</div>
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
                school: {
                    name: null,
                    address: null,
                    city: null,
                    province: 'Ontario',
                    postal_code: null,
                    phone: null,
                    is_phone_valid: true,
                    contact_salutation: 'Ms.',
                    contact_firstname: null,
                    contact_lastname: null,
                    email: null,
                    number_of_classes: null,
                    number_of_students: null
                },
                successful: null,
                error_message: null,
                backend_errors: null
			}
		},
		created() {
			axios.get('/user/url-slug').then((response) => {
				axios.get('/school/edit/' + response.data.url_slug).then((response) => {
				this.school = response.data.school;
				this.school.is_phone_valid = true;
			}).catch((error) => {
				console.log(error);
			});
			}).catch((error) => {
				console.log(error);
			});
		},
		methods: {
			submitSchool() {
				this.$validator.validate().then(result => {
                    if(result) {
                    	if (this.school.is_phone_valid == true) {
							axios.patch('/school/' + this.school.id, this.school).then((response) => {
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