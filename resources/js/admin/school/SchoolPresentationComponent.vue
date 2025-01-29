<style scoped>
	.add-rm-btn {
		margin-top: 0px;
		margin-bottom: 0px;
	}

	.guest-row {
		margin-bottom: 10px;
	}
</style>

<template>
	<div class="main-panel">
		<header-title-component page_title="School"></header-title-component>
		<div class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">{{(presentation != null) ? presentation.school_name : 'School Not Registered For The Selected Year'}}</h4>
						</div>
						<div class="card-body" v-if="presentation != null">
							<form @submit.prevent="submitPresentation" action="POST">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Dentist</label>
											<select name="dentist" class="form-control" v-model="presentation.dentist_id">
												<option :value="null">Please select a dentist</option>
												<option v-for="dentist in dentists" :value="dentist.id" :key="dentist.id">{{dentist.name}}</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Rotarian</label>
											<select name="rotarian" class="form-control" v-model="presentation.rotarian_id">
												<option :value="null">Please select a rotarian</option>
												<option v-for="rotarian in rotarians" :value="rotarian.id" :key="rotarian.id">{{rotarian.name}}</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Number of Classes</label>
											<input type="text" class="form-control" name="number_of_classes" required v-validate="'numeric|min_value:1'" data-vv-as="total number of classes" v-model="presentation.number_of_classes">
											<div class="invalid-feedback">
					                    		{{errors.first('number_of_classes')}}
					                		</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="label">Number of Students</label>
											<input type="text" class="form-control" name="number_of_students" required v-validate="'numeric|min_value:1'" data-vv-as="total number of students" v-model="presentation.number_of_students">
											<div class="invalid-feedback">
					                    		{{errors.first('number_of_students')}}
					                		</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="label">Requested Presentation Date</label>
										<input type="datetime-local" class="form-control" name="requested_presentation_date" v-model="presentation.requested_presentation_date">
									</div>
									<div class="col-md-6">
										<label class="label">School Presentation Date</label>
										<input type="datetime-local" class="form-control" name="presentation_date" v-model="presentation.presentation_date">
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="label">School Payment</label>
										<label class="control control--checkbox">Is School Payment Complete
											<input type="checkbox" v-model="presentation.is_paid">
											<div class="control__indicator"></div>
										</label>
									</div>
									<div class="col-md-6">
										<label class="label">Guests</label>
										<div class="row guest-row" v-for="(guest, index) in presentation.guests" :key="index">
											<div class="col-md-9">
												<input type="text" class="form-control" v-model="presentation.guests[index]">
											</div>
											<div class="col-md-3 text-right">
												<button class="btn btn-danger add-rm-btn" @click="removeGuestRow(index)" type="button"><i class="nc-icon nc-simple-delete"></i></button>
												<button class="btn btn-primary add-rm-btn" @click="addGuestRow()" type="button"><i class="nc-icon nc-simple-add"></i></button>
											</div>
										</div>
									</div>
									
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="success-message" v-if="successful != null && successful == true">School Presentation Update Successful</div>
					            		<div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
										<button class="btn btn-primary pull-right save-btn">Update School Presentation</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				dentists: [],
				rotarians: [],
				presentation: {
					id: null,
					number_of_classes: null,
					number_of_students: null,
					requested_presentation_date: null,
					presentation_date: null,
					dentist_id: null,
					rotarian_id: null,
					is_paid: false,
					guests: []
				},
				successful: null,
				error_message: null
			}
		},
		created() {
			this.getPresentation();
			this.getDentists();
			this.getRotarians();
		},
		methods: {
			addGuestRow() {
				this.presentation.guests.push("");
			},
			removeGuestRow(index) {
				// console.log(index)
				if(this.presentation.guests.length > 1) {
                    this.presentation.guests.splice(index, 1);
                }
			},
			getPresentation() {
				axios.get('/school/presentation/' + this.$route.params.school + '/' + this.$route.params.year).then((response) => {
					// if(response.data.presentation == null) {
					// 	this.$router.push({path: '/schools'});
					// }
					this.presentation = response.data.presentation;
					if(this.presentation.guests.length == 0) {
						this.presentation.guests.push("");
					}
				}).catch((error) => {
					console.log(error);
				});
			},
			getDentists() {
				axios.get('/dentists/registered').then((response) => {
					this.dentists = response.data.dentists;
				}).catch((error) => {
					console.log(error);
				});
			},
			getRotarians() {
				axios.get('/rotarians/registered').then((response) => {
					this.rotarians = response.data.rotarians;
				}).catch((error) => {
					console.log(error);
				});
			},
			submitPresentation() {
				this.$validator.validate().then(result => {
                    if(result) {
						axios.patch('/school/presentation/' + this.presentation.id, this.presentation).then((response) => {
							this.successful = true;
							this.$store.dispatch('getDentists');
							this.$store.dispatch('getRotarians');
							this.$store.dispatch('getSchools');
						}).catch((error) => {
							this.successful = false;
							this.error_message = error.response.data.msg;
						});
					}
				});
			}
		}
	}
</script>