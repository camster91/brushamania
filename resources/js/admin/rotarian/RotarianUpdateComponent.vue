<template>
	<div class="card-body">
		<form @submit.prevent="submitRotarian" method="POST">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Contact Salutation (required)</label>
						<select name="contact_salutation" class="form-control" v-model="rotarian.contact_salutation" required v-validate>
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
						<label class="label">Rotarian First Name (required)</label>
						<input type="text" class="form-control" name="rotarian_firstname" required v-validate v-model="rotarian.contact_firstname" data-vv-as="rotarian first name">
						<div class="invalid-feedback">
                    		{{errors.first('rotarian_firstname')}}
                    		{{(backend_errors != null) ? (backend_errors.contact_firstname != null) ? backend_errors.contact_firstname[0] : null : null}}
                		</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Rotarian Last Name (required)</label>
						<input type="text" class="form-control" name="rotarian_lastname" required v-validate v-model="rotarian.contact_lastname" data-vv-as="rotarian last name">
						<div class="invalid-feedback">
                    		{{errors.first('rotarian_lastname')}}
                    		{{(backend_errors != null) ? (backend_errors.contact_lastname != null) ? backend_errors.contact_lastname[0] : null : null}}
                		</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Phone</label>
						<vue-phone-number v-model="rotarian.phone" default-country-code="CA" :no-country-selector="true" :required="false" @update="updatePhone"></vue-phone-number>
	                    <div class="invalid-feedback" v-if="rotarian.is_phone_valid == false && rotarian.phone != ''">
	                        Phone number is invalid
	                    </div> 
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Email (required)</label>
						<input type="email" class="form-control" name="email" required v-validate v-model="rotarian.email">
						<div class="invalid-feedback">
                    		{{errors.first('email')}}
                    		{{(backend_errors != null) ? (backend_errors.email != null) ? backend_errors.email[0] : null : null}}
                		</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="label">Name of Rotary Club (required)</label>
						<input type="text" class="form-control" name="club_name" required v-validate v-model="rotarian.club_name" data-vv-as="name of rotary club">
						<div class="invalid-feedback">
                    		{{errors.first('club_name')}}
                    		{{(backend_errors != null) ? (backend_errors.club_name != null) ? backend_errors.club_name[0] : null : null}}
                		</div>
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
	                        <input type="checkbox" v-model="rotarian.is_organizing_committee" />
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
	                        <input type="checkbox" v-model="rotarian.is_lootbag_committee" />
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
	                        <input type="checkbox" v-model="rotarian.is_pr_media_committee" />
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
	                        <input type="checkbox" v-model="rotarian.is_sponsor_committee" />
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
	                        <input type="checkbox" v-model="rotarian.is_telephoning_committee" />
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
					<div class="success-message" v-if="successful != null && successful == true">Rotarian Updated Successfulyl</div>
            		<div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
					<button class="btn btn-primary pull-right save-btn">Update Rotarian</button>
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
                rotarian: {
                	id: null,
                    contact_salutation: 'Mr.',
                    contact_firstname: null,
                    contact_lastname: null,
                    phone: null,
                    is_phone_valid: true,
                    email: null,
                    club_name: null,
                    is_organizing_committee: false,
                    is_lootbag_committee: false,
                    is_pr_media_committee: false,
                    is_sponsor_committee: false,
                    is_telephoning_committee: false,
                },
                successful: null,
                error_message: null,
                backend_errors: null
			}
		},
		created() {
			axios.get('/rotarian/edit/' + this.$route.params.rotarian).then((response) => {
				this.rotarian = response.data.rotarian;
			}).catch((error) => {
				console.log(error);
			});
		},
		methods: {
			updatePhone(e) {
                console.log(e.isValid);
                this.rotarian.is_phone_valid = e.isValid;
            },
			submitRotarian() {
				this.$validator.validate().then(result => {
                    if(result) {
                    	if (this.rotarian.is_phone_valid == true || this.rotarian.phone == "" || this.rotarian.phone == null) {
							axios.patch('/rotarian/' + this.rotarian.id, this.rotarian).then((response) => {
								console.log(response.data);
								if(response.data.success) {
									this.$router.push({path:'/rotarians/' + response.data.url_slug});
									eventBus.$emit('refreshRotarian');
									this.successful = true;
									this.backend_errors = null;
									this.$store.dispatch('getRotarians');
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