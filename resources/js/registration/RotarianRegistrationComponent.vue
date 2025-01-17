<template>
    <div class="card-body">
        <logos-row-component></logos-row-component>
        <h2 class="title">Rotarian Registration</h2>
        <form @submit.prevent="submitRotarian" method="POST">
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label information-label">All information entered is displayed on our website except email addresses.</label>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">Salutation</label>
                    <select name="contact_salutation" v-model="rotarian.contact_salutation" class="input--style-4" required v-validate data-vv-as="salutation">
                        <option value="Dr.">Dr</option>
                        <option value="Ms.">Ms</option>
                        <option value="Miss">Miss</option>
                        <option value="Mrs.">Mrs</option>
                        <option value="Mr.">Mr</option>
                    </select>
                    <div class="invalid-input">
                        {{errors.first('contact_salutation')}}
                        {{(backend_errors != null) ? (backend_errors.contact_salutation != null) ? backend_errors.contact_salutation[0] : null : null}}
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="label">Rotarian First Name (required)</label>
                    <input type="text" class="input--style-4" name="rotarian_firstname" required v-validate data-vv-as="first name" v-model="rotarian.contact_firstname">
                    <div class="invalid-input">
                        {{errors.first('rotarian_firstname')}}
                        {{(backend_errors != null) ? (backend_errors.contact_firstname != null) ? backend_errors.contact_firstname[0] : null : null}}
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">Rotarian Last Name (required) </label>
                    <input type="text" class="input--style-4" name="rotarian_lastname" required v-validate data-vv-as="last name" v-model="rotarian.contact_lastname">
                    <div class="invalid-input">
                        {{errors.first('rotarian_lastname')}}
                        {{(backend_errors != null) ? (backend_errors.contact_lastname != null) ? backend_errors.contact_lastname[0] : null : null}}
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="label">Phone</label>
                    <vue-phone-number v-model="rotarian.phone" default-country-code="CA" :no-country-selector="true" :required="false" @update="updatePhone"></vue-phone-number>
                    <div class="invalid-input" v-if="rotarian.is_phone_valid == false && rotarian.phone != ''" style="margin-top: 10px">
                        Phone number is invalid
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">Your Email (required) </label>
                    <input type="email" class="input--style-4" name="email" required v-validate data-vv-as="email" v-model="rotarian.email">
                    <div class="invalid-input">
                        {{errors.first('email')}}
                        {{(backend_errors != null) ? (backend_errors.email != null) ? backend_errors.email[0] : null : null}}
                    </div>
                </div>
                <div class="col-md-6">
                   <label class="label">Name of Rotary Club</label>
                    <input type="text" class="input--style-4" name="club_name" v-model="rotarian.club_name">
                </div>
            </div>
            <div class="row row-space" style="margin-top: 30px;">
                <div class="col-md-12">
                    <label class="label"><b>School Selection</b> (You may attend more than one school during the month of April.)</label>
                </div>
                <div class="col-md-6">
                    <div class="radio">
                        <label class="control control--radio">Select a school(s) already registered for Brush-a-mania 2024 that needs a Rotarian.
                            <input type="radio" name="will_enter_school" v-model="rotarian.will_enter_school" :value="2" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="radio">
                        <label class="control control--radio">I have contacted a school and dentist to register for Brush-a-mania.
                            <input type="radio" name="will_enter_school" v-model="rotarian.will_enter_school" :value="1" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
            </div>
            <div v-if="rotarian.will_enter_school == 2 && rotarian.will_enter_school != null">
                <div class="row row-space d-inline-block w-100">
                    <div class="col-md-6 d-inline-block">
                        <input type="text" class="input--style-4" name="school_search_postal_code" v-model="school_search_postal_code" placeholder="Enter your postal code" autocomplete="nope">
                        <label>To see the full list of schools registered for 2024, click <a href="https://brushamania.ca/school-presentation" target="_blank">here</a>.</label>
                    </div>
                    <!-- <div class="col-md-2 d-inline-block">
                        <button type="button" class="btn btn-primary" @click="getSchools">Search</button>
                    </div> -->
                </div>
                <div class="row row-space" v-if="school_search_postal_code.length >= 1">
                    <div v-if="searchSchools.length >= 1">
                        <div class="col-md-12" v-for="school in searchSchools" :key="school.name">
                            <div class="checkbox">
                                <label class="control control--checkbox">{{school.name}}
                                    <input type="checkbox" :value="school.id" v-model="rotarian.selected_schools" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" v-else>
                        <label>There are no schools registered in postal code {{school_search_postal_code}}.</label>
                        <br><br>
                        <label>Please invite your school to register by using our <a href="https://brushamania.ca/wp-content/uploads/sites/2/2022/01/Letter-School-Invite.pdf" target="_blank">Letter to School Principals.</a></label>
                    </div>
                </div>
                <div class="row row-space">
                    <div class="col-md-12">
                        <div class="label information-label">
                            All supplies required to run the program will be delivered to participating schools the week of March 18, 2025.
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="rotarian.will_enter_school == 1 && rotarian.will_enter_school != null">
                <div class="row row-space">
                    <div class="col-md-12">
                        <div class="label">
                            Dentist Info
                        </div>
                    </div>
                </div>
                <div class="row row-space">
                    <div class="col-md-6">
                        <label class="label">Dentist First Name (required) </label>
                        <input type="text" class="input--style-4" name="dentist_firstname" required v-validate data-vv-as="dentist first name" v-model="rotarian.dentist_firstname">
                        <div class="invalid-input">
                            {{errors.first('dentist_firstname')}}
                            {{(backend_errors != null) ? (backend_errors.dentist_firstname != null) ? backend_errors.dentist_firstname[0] : null : null}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="label">Dentist Last Name (required) </label>
                        <input type="text" class="input--style-4" name="dentist_lastname" required v-validate data-vv-as="dentist last name" v-model="rotarian.dentist_lastname">
                        <div class="invalid-input">
                            {{errors.first('dentist_lastname')}}
                            {{(backend_errors != null) ? (backend_errors.dentist_lastname != null) ? backend_errors.dentist_lastname[0] : null : null}}
                        </div>
                    </div>
                </div>
                <div class="row row-space">
                    <div class="col-md-6">
                        <label class="label">Dentist Phone</label>
                        <vue-phone-number v-model="rotarian.dentist_phone" default-country-code="CA" :no-country-selector="true" :required="false" @update="updateDentistPhone"></vue-phone-number>
                        <div class="invalid-input" v-if="rotarian.is_dentist_phone_valid == false" style="margin-top: 10px">
                            Phone number is invalid
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="label">Dentist Email (required) </label>
                        <input type="email" class="input--style-4" name="dentist_email" required v-validate data-vv-as="dentist email" v-model="rotarian.dentist_email">
                        <div class="invalid-input">
                            {{errors.first('dentist_email')}}
                            {{(backend_errors != null) ? (backend_errors.dentist_email != null) ? backend_errors.dentist_email[0] : null : null}}
                        </div>
                    </div>
                </div>
                <div class="row row-space">
                    <div class="col-md-12">
                        <div class="label">
                            School Info
                        </div>
                    </div>
                </div>
                <div class="row row-space">
                    <div class="col-md-12">
                        <label class="label">School Name (required) </label>
                        <input type="text" class="input--style-4" name="school_name" required v-validate data-vv-as="school name" v-model="rotarian.school_name">
                        <div class="invalid-input">
                            {{errors.first('school_name')}}
                            {{(backend_errors != null) ? (backend_errors.school_name != null) ? backend_errors.school_name[0] : null : null}}
                        </div>
                    </div>
                </div>
                <div class="row row-space">
                    <div class="col-md-6">
                        <label class="label">School Contact First Name</label>
                        <input type="text" class="input--style-4" name="school_contact_firstname" data-vv-as="school contact first name" v-model="rotarian.school_contact_firstname">
                    </div>
                    <div class="col-md-6">
                        <label class="label">School Contact Last Name</label>
                        <input type="text" class="input--style-4" name="school_contact_lastname" data-vv-as="school contact last name" v-model="rotarian.school_contact_lastname">
                    </div>
                </div>
                <div class="row row-space">
                    <div class="col-md-6">
                        <label class="label">School Email (required) </label>
                        <input type="email" class="input--style-4" name="school_email" required v-validate data-vv-as="school email" v-model="rotarian.school_email">
                        <div class="invalid-input">
                            {{errors.first('school_email')}}
                            {{(backend_errors != null) ? (backend_errors.school_email != null) ? backend_errors.school_email[0] : null : null}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="label">Enter the date and time of your school presentation <strong>ONLY</strong> if confirmed with <strong>BOTH</strong> your school and dentist.</label>
                        <input type="datetime-local" class="input--style-4" name="presentation_date" v-model="rotarian.presentation_date">
                        <div class="invalid-input">
                            {{(backend_errors != null) ? (backend_errors.presentation_date != null) ? backend_errors.presentation_date[0] : null : null}}
                        </div>
                    </div>
                </div>
                <div class="row row-space">
                    <div class="col-md-12">
                        <div class="label information-label">
                            All supplies required to run the program will be delivered to participating schools the week of March 18, 2025.
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label">I would like to help out on the following committees:</label>
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
                    <div class="invalid-input">
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
                    <div class="invalid-input">
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
                    <div class="invalid-input">
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
                    <div class="invalid-input">
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
                        <div class="invalid-input">
                        {{(backend_errors != null) ? (backend_errors.is_telephoning_committee != null) ? backend_errors.is_sponsor_committee[0] : null : null}}
                    </div>
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <div class="label">
                        For more information, contact Rotarian Jennifer Boyd at: <a href="mailto:jennifer@brushamania.ca">jennifer@brushamania.ca</a>
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <google-re-captcha-v3
                        ref="captcha" v-model="rotarian.gRecaptchaResponse"
                        :siteKey="siteKey"
                        :id="'rotarian_registration_id'"
                        :inline="true"
                        :action="'rotarian_registration'">
                    </google-re-captcha-v3>
                </div>
            </div>
            <div class="p-t-15">
                <button class="btn btn--radius-2 btn--blue pull-right" type="submit">Submit</button>
            </div>

            <div class="success-message" v-if="successful != null && successful == true">Rotarian Registered Successfully</div>
            <div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                rotarian: {
                    contact_firstname: null,
                    contact_lastname: null,
                    email: null,
                    phone: null,
                    contact_salutation: 'Mr.',
                    is_phone_valid: null,
                    club_name: null,
                    is_organizing_committee: false,
                    is_lootbag_committee: false,
                    is_pr_media_committee: false,
                    is_sponsor_committee: false,
                    is_telephoning_committee: false,
                    selected_schools: [],
                    school_name: null,
                    school_contact_firstname: null,
                    school_contact_lastname: null,
                    school_email: null,
                    dentist_firstname: null,
                    dentist_lastname: null,
                    dentist_phone: null,
                    is_dentist_phone_valid: null,
                    dentist_email: null,
                    presentation_date: null,
                    gRecaptchaResponse: null
                },
                schools: [],
                school_search_postal_code: "",
                successful: null,
                error_message: null,
                backend_errors: null,
                siteKey: '6LcyOrYqAAAAACLkY9tYPLxrWqh2Qg-gQE9JKfLO'
            }
        },
        created() {
            this.getSchools();
        },
        computed: {
            searchSchools() {
                let tempSchools = this.schools.filter(school => {
                    return school.postal_code.toLowerCase().startsWith(this.school_search_postal_code.toLowerCase());
                });
                return tempSchools;
            }
        },
        methods: {
            getSchools() {
                axios.get('/presentation/schools/no-rotarian/' + this.school_search_postal_code)
                    .then((response) => {
                        this.schools = response.data.schools;
                    }).catch((error) => {
                        console.log(error);
                    })
            },
            updatePhone(e) {
                console.log(e.isValid);
                this.rotarian.is_phone_valid = e.isValid;
            },
            updateDentistPhone(e) {
                console.log(e.isValid);
                this.rotarian.is_dentist_phone_valid = e.isValid;
            },
            submitRotarian() {
                this.$validator.validate().then(result => {
                    if(result) {
                        if (this.rotarian.is_phone_valid == true || this.rotarian.phone == "" || this.rotarian.phone == null) {
                            axios.post('/rotarian', this.rotarian)
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
                    }
                });
            },
            resetData() {
                this.rotarian = {
                    contact_firstname: null,
                    contact_lastname: null,
                    email: null,
                    phone: null,
                    contact_salutation: 'Mr.',
                    is_phone_valid: null,
                    club_name: null,
                    is_organizing_committee: false,
                    is_lootbag_committee: false,
                    is_pr_media_committee: false,
                    is_sponsor_committee: false,
                    is_telephoning_committee: false,
                    selected_schools: [],
                    school_name: null,
                    school_contact_firstname: null,
                    school_contact_lastname: null,
                    school_email: null,
                    dentist_firstname: null,
                    dentist_lastname: null,
                    dentist_phone: null,
                    is_dentist_phone_valid: null,
                    dentist_email: null,
                    presentation_date: null,
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
