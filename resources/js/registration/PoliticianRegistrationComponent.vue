<template>
	<div class="card-body">
		<form @submit.prevent="submitPolitician" method="POST">
			<div class="row row-space">
				<div class="col-md-6">
					<label class="label">First Name</label>
					<input type="text" class="input--style-4" name="politician_firstname" required v-validate data-vv-as="first name" v-model="politician.firstname">
                    <div class="invalid-input">
                        {{errors.first('politician_firstname')}}
                        {{(backend_errors != null) ? (backend_errors.firstname != null) ? backend_errors.firstname[0] : null : null}}
                    </div>
				</div>
				<div class="col-md-6">
					<label class="label">Last Name</label>
					<input type="text" class="input--style-4" name="politician_lastname" required v-validate data-vv-as="first name" v-model="politician.lastname">
                    <div class="invalid-input">
                        {{errors.first('politician_lastname')}}
                        {{(backend_errors != null) ? (backend_errors.lastname != null) ? backend_errors.lastname[0] : null : null}}
                    </div>
				</div>
			</div>
			<div class="row row-space">
				<div class="col-md-6">
					<label class="label">Email</label>
					<input type="email" class="input--style-4" name="email" required v-validate data-vv-as="email" v-model="politician.email">
                    <div class="invalid-input">
                        {{errors.first('email')}}
                        {{(backend_errors != null) ? (backend_errors.email != null) ? backend_errors.email[0] : null : null}}
                    </div>
				</div>
				<div class="col-md-6">
					<label class="label">Phone</label>
					<input type="tel" class="input--style-4" name="phone" required v-validate data-vv-as="phone number" v-model="politician.phone">
                    <div class="invalid-input">
                        {{errors.first('phone')}}
                        {{(backend_errors != null) ? (backend_errors.phone != null) ? backend_errors.phone[0] : null : null}}
                    </div>
				</div>
			</div>
			<div class="row row-space">
                <div class="col-md-12">
                    <google-re-captcha-v3
                        ref="captcha" v-model="politician.gRecaptchaResponse"
                        :siteKey="siteKey"
                        :id="'politician_registration_id'"
                        :inline="true"
                        :action="'politician_registration'">
                    </google-re-captcha-v3>
                </div>
            </div>
            <div class="p-t-15">
                <button class="btn btn--radius-2 btn--blue pull-right" type="submit">Submit</button>
            </div>

            <div class="success-message" v-if="successful != null && successful == true">Politician Registered Successfully</div>
            <div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
		</form>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				politician: {
					firstname: null,
					lastname: null,
					email: null,
					phone: null
				},
				successful: null,
				error_message: null,
				backend_errors: null,
				siteKey: '6LcyOrYqAAAAACLkY9tYPLxrWqh2Qg-gQE9JKfLO'
			}
		},
		methods: {
			submitPolitician() {
				this.$validator.validate().then(result => {
                    if(result) {
                        axios.post('/politician', this.politician)
                        .then((response) => {
                            console.log(response.data);
                            this.successful = true;
                            this.resetData();
                            this.$refs.captcha.execute();
                        }).catch((error) => {
                            this.successful = false;
                            this.error_message = error.response.data.msg;
                            if(error.response.data.msg != null) {
                                this.error_message = error.response.data.msg;
                            } else {
                                this.error_message = error.response.data.message;
                            }
                            this.backend_errors = error.response.data.errors;
                            this.$refs.captcha.execute();
                        });
                    }
                });
			},
			resetData() {
                this.politician = {
					firstname: null,
					lastname: null,
					email: null,
					phone: null
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