<style scoped>
    #postcard-img {
        width: 100%;
    }
    @media only screen and (min-width: 1200px){
       #postcard-img {
            width: 50%;
        }
    }
</style>

<template>
    <div class="card-body">
        <logos-row-component></logos-row-component>
        <h2 class="title">Dentist Registration</h2>
        <form @submit.prevent="submitDentist" method="POST" ref="addDentist">
            <input autocomplete="false" name="hidden" type="text" style="display:none;">
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label information-label">All information entered is displayed on our website except email addresses.</label>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">Clinic Name</label>
                    <input type="text" class="input--style-4" name="clinic_name" v-validate data-vv-as="clinic name" v-model="dentist.clinic_name">
                    <div class="invalid-input">
                        {{errors.first('clinic_name')}}
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="label">Dentist First Name (required) </label>
                    <input type="text" class="input--style-4" name="dentist_firstname" required v-validate data-vv-as="dentist first name" v-model="dentist.dentist_firstname">
                    <div class="invalid-input">
                        {{errors.first('dentist_firstname')}}
                        {{(backend_errors != null) ? (backend_errors.dentist_firstname != null) ? backend_errors.dentist_firstname[0] : null : null}}
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">Dentist Last Name (required)</label>
                    <input type="text" class="input--style-4" name="dentist_lastname" required v-validate data-vv-as="dentist last name" v-model="dentist.dentist_lastname">
                    <div class="invalid-input">
                        {{errors.first('dentist_lastname')}}
                        {{(backend_errors != null) ? (backend_errors.dentist_lastname != null) ? backend_errors.dentist_lastname[0] : null : null}}
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="label">Contact Salutation </label>
                    <select name="dentist_contact_salutation" v-model="dentist.contact_salutation" class="input--style-4" required v-validate data-vv-as="dentist contact salutation">
                        <option value="Dr.">Dr</option>
                        <option value="Ms.">Ms</option>
                        <option value="Miss">Miss</option>
                        <option value="Mrs.">Mrs</option>
                        <option value="Mr.">Mr</option>
                    </select>
                    <div class="invalid-input">
                        {{errors.first('dentist_contact_salutation')}}
                        {{(backend_errors != null) ? (backend_errors.contact_salutation != null) ? backend_errors.contact_salutation[0] : null : null}}
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">Contact First Name</label>
                    <input type="text" class="input--style-4" name="dentist_contact_firstname" v-model="dentist.contact_firstname">
                </div>
                <div class="col-md-6">
                    <label class="label">Contact Last Name</label>
                    <input type="text" class="input--style-4" name="dentist_contact_lastname" v-model="dentist.contact_lastname">
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">Email (required) </label>
                    <input type="email" class="input--style-4" name="dentist_email" required v-validate data-vv-as="dentist email" v-model="dentist.email" ref="dentist_email">
                    <div class="invalid-input">
                        {{errors.first('dentist_email')}}
                        {{(backend_errors != null) ? (backend_errors.email != null) ? backend_errors.email[0] : null : null}}
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="label">Confirm Email (required)</label>
                    <input type="email" class="input--style-4" name="confirm_dentist_email" required v-validate="'confirmed:dentist_email'" data-vv-as="dentist email confirmation" v-model="dentist.email_confirmation">
                    <div class="invalid-input">
                        {{errors.first('confirm_dentist_email')}}
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">Address</label>
                    <input type="text" class="input--style-4" name="address" v-model="dentist.address">
                </div>
                <div class="col-md-6">
                    <label class="label">City</label>
                    <input type="text" class="input--style-4" name="city" v-model="dentist.city">
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">Province</label>
                    <select name="province" v-model="dentist.province" class="input--style-4">
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
                <div class="col-md-6">
                    <label class="label">Postal Code</label>
                    <input type="text" class="input--style-4" name="postal_code" data-vv-as="postal code" v-model="dentist.postal_code">
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">Phone (required)</label>
                    <vue-phone-number v-model="dentist.phone" default-country-code="CA" :no-country-selector="true" :required="true" @update="updatePhone"></vue-phone-number>
                    <div class="invalid-input" v-if="dentist.is_phone_valid == false" style="margin-top: 10px">
                        Phone number is invalid
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="label">Website</label>
                    <input type="text" class="input--style-4" name="website" v-validate="'url'" v-model="dentist.website">
                    <div class="invalid-input">
                        {{errors.first('website')}}
                    </div>
                </div>
            </div>
            <div class="row row-space" style="margin-top: 30px;">
                <div class="col-md-12">
                    <label class="label"><b>School Selection</b> (You may attend more than one school during the month of April.)</label>
                </div>
                <div class="col-md-4">
                    <div class="radio">
                        <label class="control control--radio">Select a school(s) already registered for Brush-a-mania 2024 that needs a dentist.
                            <input type="radio" name="will_enter_school" v-model="dentist.will_enter_school" :value="2" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="radio">
                        <label class="control control--radio">I have contacted a school to register for Brush-a-mania.
                            <input type="radio" name="will_enter_school" v-model="dentist.will_enter_school" :value="1" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="radio">
                        <label class="control control--radio">I will enter the school’s contact later.
                            <input type="radio" name="will_enter_school" v-model="dentist.will_enter_school" :value="0" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
            </div>
            <div v-if="dentist.will_enter_school == 1 && dentist.will_enter_school != null">
                <div class="row row-space">
                    <div class="col-md-12">
                        <label class="label">School Name (required) </label>
                        <input type="text" class="input--style-4" name="school_name" required v-validate data-vv-as="school name" v-model="dentist.school_name">
                        <div class="invalid-input">
                            {{errors.first('school_name')}}
                            {{(backend_errors != null) ? (backend_errors.school_name != null) ? backend_errors.school_name[0] : null : null}}
                        </div>
                    </div>
                </div>
                <div class="row row-space">
                    <div class="col-md-6">
                        <label class="label">School Contact First Name</label>
                        <input type="text" class="input--style-4" name="school_contact_firstname" data-vv-as="school contact first name" v-model="dentist.school_contact_firstname">
                    </div>
                    <div class="col-md-6">
                        <label class="label">School Contact Last Name</label>
                        <input type="text" class="input--style-4" name="school_contact_lastname" data-vv-as="school contact last name" v-model="dentist.school_contact_lastname">
                    </div>
                </div>
                <div class="row row-space">
                    <div class="col-md-6">
                        <label class="label">School Email (required) </label>
                        <input type="email" class="input--style-4" name="school_email" required v-validate data-vv-as="school email" v-model="dentist.school_email">
                        <div class="invalid-input">
                            {{errors.first('school_email')}}
                            {{(backend_errors != null) ? (backend_errors.school_email != null) ? backend_errors.school_email[0] : null : null}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="label">Enter the date and time of your school presentation only if confirmed with your school.</label>
                        <input type="datetime-local" class="input--style-4" name="presentation_date" v-model="dentist.presentation_date">
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
            <div v-if="dentist.will_enter_school == 2 && dentist.will_enter_school != null">
                <div class="row row-space d-inline-block w-100">
                    <div class="col-md-6 d-inline-block">
                        <input type="text" class="input--style-4" name="school_search_postal_code" v-model="school_search_postal_code" placeholder="Enter your postal code" autocomplete="nope">
                        <label>To see the full list of schools registered for 2024, click <a href="https://brushamania.ca/school-presentation" target="_blank">here</a>.</label>
                    </div>
                </div>
                <div class="row row-space" v-if="school_search_postal_code.length >= 1">
                    <div v-if="searchSchools.length >= 1">
                        <div class="col-md-12" v-for="school in searchSchools" :key="school.name">
                            <div class="checkbox">
                                <label class="control control--checkbox">{{school.name}}
                                    <input type="checkbox" :value="school.id" v-model="dentist.selected_schools" />
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
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label">Send me free Brush-a-mania postcards to introduce Brush-a-mania to my patients and to encourage
                        reviews for my clinic on <a href="https://dentistfind.com">DentistFind.com</a> <strong>Not available this year.</strong></label>
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
                    <div class="invalid-input">
                        {{(backend_errors != null) ? (backend_errors.postcard != null) ? backend_errors.postcard[0] : null : null}}
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <img src="/img/Postcard1-1.png" alt="postcard" id="postcard-img">
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label">There is no charge if you pick up your cards from your local dental society meeting. If you wish the cards shipped to your office, a $10 shipping charge will apply.</label>
                    <div class="checkbox">
                        <label class="control control--checkbox">I will pick up at my local society meeting
                            <input type="checkbox" v-model="dentist.will_pick_up_local" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="invalid-input">
                        {{(backend_errors != null) ? (backend_errors.will_pick_up_local != null) ? backend_errors.will_pick_up_local[0] : null : null}}
                    </div>
                </div>
            </div>
            <div class="row row-space" v-if="dentist.will_pick_up_local">
                <div class="col-md-12">
                    <label class="label">Name of your dental society </label>
                    <input type="text" class="input--style-4" name="dental_society_name" required v-validate data-vv-as="dental society name" v-model="dentist.dental_society_name">
                    <div class="invalid-input">
                        {{errors.first('dental_society_name')}}
                        {{(backend_errors != null) ? (backend_errors.dental_society_name != null) ? backend_errors.dental_society_name[0] : null : null}}
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
                            <input type="checkbox" v-model="dentist.is_organizing_committee" />
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
                            <input type="checkbox" v-model="dentist.is_lootbag_committee" />
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
                            <input type="checkbox" v-model="dentist.is_pr_media_committee" />
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
                            <input type="checkbox" v-model="dentist.is_sponsor_committee" />
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
                            <input type="checkbox" v-model="dentist.is_telephoning_committee" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="invalid-input">
                        {{(backend_errors != null) ? (backend_errors.is_telephoning_committee != null) ? backend_errors.is_telephoning_committee[0] : null : null}}
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <div class="label">
                        For more information, contact Raffy Chouljian at <a href="mailto:raffy@brushamania.ca">raffy@brushamania.ca</a>
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <div class="checkbox">
                        <label class="control control--checkbox add-to-df">Add my clinic to <a href="https://dentistfind.com" style="display: contents;">DentistFind.com</a> to attract more patients. Also add me to the review list so patients can post reviews for me once they scan the code on the postcard (Free for all Brush-a-mania participating dentist)
                            <input type="checkbox" v-model="dentist.add_clinic_to_dentistfind" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="invalid-input">
                        {{(backend_errors != null) ? (backend_errors.add_clinic_to_dentistfind != null) ? backend_errors.add_clinic_to_dentistfind[0] : null : null}}
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <google-re-captcha-v3
                        ref="captcha" v-model="dentist.gRecaptchaResponse"
                        :siteKey="siteKey"
                        :id="'dentist_registration_id'"
                        :inline="true"
                        :action="'dentist_registration'">
                    </google-re-captcha-v3>
                </div>
            </div>
            <div class="p-t-15">
                <button class="btn btn--radius-2 btn--blue pull-right" type="submit">Submit</button>
            </div>

            <div class="success-message" v-if="successful != null && successful == true">Dentist Registered Successfully</div>
            <div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                dentist: {
                    clinic_name: null,
                    dentist_firstname: null,
                    dentist_lastname: null,
                    contact_salutation: 'Dr.',
                    contact_firstname: null,
                    contact_lastname: null,
                    email: null,
                    email_confirmation: null,
                    address: null,
                    city: null,
                    province: 0,
                    postal_code: null,
                    phone: null,
                    is_phone_valid: null,
                    website: null,
                    school_name: null,
                    school_contact_firstname: null,
                    school_contact_lastname: null,
                    school_email: null,
                    will_enter_school: null,
                    presentation_date: null,
                    postcard: 0,
                    will_pick_up_local: false,
                    dental_society_name: null,
                    is_organizing_committee: false,
                    is_lootbag_committee: false,
                    is_pr_media_committee: false,
                    is_sponsor_committee: false,
                    is_telephoning_committee: false,
                    selected_schools: [],
                    add_clinic_to_dentistfind: false,
                    invite_token: null,
                    old_invite_token: null,
                    gRecaptchaResponse: null,
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
            this.dentist.old_invite_token = new URL(location.href).searchParams.get('invite_token');
        },
        computed: {
            searchSchools() {
                let tempSchools = [];
                if(this.school_search_postal_code.length >= 1) {
                    tempSchools = this.schools.filter(school => {
                        return school.postal_code.toLowerCase().startsWith(this.school_search_postal_code.toLowerCase());
                    });
                }
                return tempSchools;
            }
        },
        methods: {
            getSchools() {
                axios.get('/presentation/schools/no-dentist/' + this.school_search_postal_code)
                    .then((response) => {
                        this.schools = response.data.schools;
                    }).catch((error) => {
                        console.log(error);
                    })
            },
            updatePhone(e) {
                this.dentist.is_phone_valid = e.isValid;
            },
            submitDentist() {
                this.dentist.old_invite_token = new URL(location.href).searchParams.get('invite_token');
                this.$validator.validate().then(result => {
                    if(result) {
                        if (this.dentist.is_phone_valid == true) {
                            if ((this.dentist.contact_firstname == null || this.dentist.contact_firstname == "") && (this.dentist.contact_lastname == null || this.dentist.contact_lastname == "")) {
                                this.dentist.contact_firstname = this.dentist.dentist_firstname;
                                this.dentist.contact_lastname = this.dentist.dentist_lastname;
                            }
                            axios.post('/dentist', this.dentist)
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
                this.dentist = {
                    clinic_name: null,
                    dentist_firstname: null,
                    dentist_lastname: null,
                    contact_salutation: 'Dr.',
                    contact_firstname: null,
                    contact_lastname: null,
                    email: null,
                    email_confirmation: null,
                    address: null,
                    city: null,
                    province: 0,
                    postal_code: null,
                    is_phone_valid: null,
                    phone: null,
                    website: null,
                    school_name: null,
                    school_contact_firstname: null,
                    school_contact_lastname: null,
                    school_email: null,
                    will_enter_school: null,
                    presentation_date: null,
                    postcard: null,
                    will_pick_up_local: false,
                    dental_society_name: null,
                    is_organizing_committee: false,
                    is_lootbag_committee: false,
                    is_pr_media_committee: false,
                    is_sponsor_committee: false,
                    is_telephoning_committee: false,
                    add_clinic_to_dentistfind: false,
                    gRecaptchaResponse: null,
                    old_invite_token: null
                };
                this.$validator.pause();
                this.$nextTick(() => {
                    this.$validator.errors.clear();
                    this.$validator.fields.items.forEach(field => field.reset());
                    this.$validator.fields.items.forEach(field => this.errors.remove(field));
                    this.$validator.resume();
                });
;            }
        }
    }
</script>
