<style>
.autocomplete-input {
    background-image: none;
    background-color: #fafafa;
    padding-left: 20px;
    padding-right: 20px;
}
.autocomplete-input:focus {
    background-color: #fafafa;
    box-shadow: inset 0px 1px 3px 0px rgba(0, 0, 0, 0.08);
}
.school-name {
    font-size: 20px;
}

.school-city {
    font-size: 16px;
}

.modal-dialog {
    top: 30%;
    max-width: 700px;
}

.modal-dialog .modal-content {
    padding: 10px 20px;
}

input:read-only,
select:disabled {
    cursor: not-allowed;
}
</style>
<template>
    <div class="card-body">
        <div
            class="modal fade"
            tabindex="-1"
            role="dialog"
            id="close-registration-modal"
        >
            <!-- <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">
                            School registrations are now closed
                        </h2>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <p style="font-size: 20px" class="p-4">
                        All supplies required to run the program will be delivered to participating schools the week of March 17, 2025.<br />
                    </p>
                </div>
            </div> -->
        </div>
        <logos-row-component></logos-row-component>
        <h2 class="title">School Registration</h2>
        <div class="row row-space">
            <div class="col-md-12">
                <div class="label">
                    To register for the Brush-a-mania Student contest, click
                    <a href="https://app.brushamania.ca/parent-registration"
                        >here.</a
                    >
                </div>
            </div>
        </div>
        <form @submit.prevent="submitSchool" method="POST">
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label information-label"
                        >All information entered is displayed on our website
                        except email addresses.</label
                    >
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label" v-if="read_only" style="color: red"
                        ><b
                            >If you wish to correct your school name, address or
                            phone number, please contact Jennifer Boyd at
                            <a href="mailto: jennifer@brushamania.ca"
                                >jennifer@brushamania.ca</a
                            ></b
                        ></label
                    >
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label">School Name (required) </label>
                    <autocomplete
                        :search="search"
                        @submit="autofillSchool"
                        :get-result-value="getResultValue"
                        @keyup="read_only = false"
                    >
                        <template #result="{ result, props }">
                            <li
                                v-bind="props"
                                class="autocomplete-result wiki-result"
                            >
                                <div class="school-name">
                                    {{ result.name }}
                                </div>
                                <div class="school-city">
                                    {{ result.city }}
                                </div>
                            </li>
                        </template>
                    </autocomplete>
                    <div class="invalid-input">
                        {{ errors.first("name") }}
                        {{
                            backend_errors != null
                                ? backend_errors.name != null
                                    ? backend_errors.name[0]
                                    : null
                                : null
                        }}
                    </div>
                </div>
            </div>
            <!-- <div class="row row-space">
                <div class="col-md-8">
                    <label class="label">School Name (required) </label>
                    <input type="text" class="input--style-4" name="name" required v-validate data-vv-as="school name" v-model="school.name">
                    <div class="invalid-input">
                        {{errors.first('name')}}
                        {{(backend_errors != null) ? (backend_errors.name != null) ? backend_errors.name[0] : null : null}}
                    </div>
                </div>
                <div class="col-md-4">
                    <span class="btn btn--radius-2 btn--blue" style="margin-top: 29px;" @click="autofillSchool">Autofill</span>
                </div>
            </div> -->
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">Address (required)</label>
                    <input
                        type="text"
                        class="input--style-4"
                        name="address"
                        :readonly="read_only"
                        required
                        v-validate
                        v-model="school.address"
                    />
                    <div class="invalid-input">
                        {{ errors.first("address") }}
                        {{
                            backend_errors != null
                                ? backend_errors.address != null
                                    ? backend_errors.address[0]
                                    : null
                                : null
                        }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="label">City (required) </label>
                    <input
                        type="text"
                        class="input--style-4"
                        name="city"
                        :readonly="read_only"
                        required
                        v-validate
                        v-model="school.city"
                    />
                    <div class="invalid-input">
                        {{ errors.first("city") }}
                        {{
                            backend_errors != null
                                ? backend_errors.city != null
                                    ? backend_errors.city[0]
                                    : null
                                : null
                        }}
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label"> </label>
                    <select
                        name="province"
                        v-model="school.province"
                        :disabled="read_only"
                        class="input--style-4"
                        required
                        v-validate
                    >
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
                    <div class="invalid-input">
                        {{ errors.first("province") }}
                        {{
                            backend_errors != null
                                ? backend_errors.province != null
                                    ? backend_errors.province[0]
                                    : null
                                : null
                        }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="label">Postal Code (required) </label>
                    <input
                        type="text"
                        class="input--style-4"
                        name="postal_code"
                        :readonly="read_only"
                        required
                        v-validate
                        data-vv-as="postal code"
                        v-model="school.postal_code"
                    />
                    <div class="invalid-input">
                        {{ errors.first("postal_code") }}
                        {{
                            backend_errors != null
                                ? backend_errors.postal_code != null
                                    ? backend_errors.postal_code[0]
                                    : null
                                : null
                        }}
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">Phone (required)</label>
                    <vue-phone-number
                        v-model="school.phone"
                        default-country-code="CA"
                        :disabled="read_only"
                        :no-country-selector="true"
                        :required="true"
                        @update="updatePhone"
                    ></vue-phone-number>
                    <div
                        class="invalid-input"
                        v-if="school.is_phone_valid == false"
                        style="margin-top: 10px"
                    >
                        Phone number is invalid
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="label"
                        >School Contact Salutation (required)
                    </label>
                    <select
                        name="contact_salutation"
                        v-model="school.contact_salutation"
                        class="input--style-4"
                        required
                        v-validate
                        data-vv-as="school contact salutation"
                    >
                        <option value="Dr.">Dr</option>
                        <option value="Ms.">Ms</option>
                        <option value="Miss">Miss</option>
                        <option value="Mrs.">Mrs</option>
                        <option value="Mr.">Mr</option>
                    </select>
                    <div class="invalid-input">
                        {{ errors.first("contact_salutation") }}
                        {{
                            backend_errors != null
                                ? backend_errors.contact_salutation != null
                                    ? backend_errors.contact_salutation[0]
                                    : null
                                : null
                        }}
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label"
                        >School Contact First Name (required)
                    </label>
                    <input
                        type="text"
                        class="input--style-4"
                        name="contact_firstname"
                        required
                        v-validate
                        data-vv-as="school contact first name"
                        v-model="school.contact_firstname"
                    />
                    <div class="invalid-input">
                        {{ errors.first("contact_firstname") }}
                        {{
                            backend_errors != null
                                ? backend_errors.contact_firstname != null
                                    ? backend_errors.contact_firstname[0]
                                    : null
                                : null
                        }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="label"
                        >School Contact Last Name (required)</label
                    >
                    <input
                        type="text"
                        class="input--style-4"
                        name="contact_lastname"
                        required
                        v-validate
                        data-vv-as="school contact last name"
                        v-model="school.contact_lastname"
                    />
                    <div class="invalid-input">
                        {{ errors.first("contact_lastname") }}
                        {{
                            backend_errors != null
                                ? backend_errors.contact_lastname != null
                                    ? backend_errors.contact_lastname[0]
                                    : null
                                : null
                        }}
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">School Email (required) </label>
                    <input
                        type="email"
                        class="input--style-4"
                        name="email"
                        required
                        v-validate
                        data-vv-as="school email"
                        v-model="school.email"
                        ref="email"
                    />
                    <div class="invalid-input">
                        {{ errors.first("email") }}
                        {{
                            backend_errors != null
                                ? backend_errors.email != null
                                    ? backend_errors.email[0]
                                    : null
                                : null
                        }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="label">Confirm School Email (required)</label>
                    <input
                        type="email"
                        class="input--style-4"
                        name="confirm_email"
                        required
                        v-validate="'confirmed:email'"
                        data-vv-as="school email"
                        v-model="school.email_confirmation"
                    />
                    <div class="invalid-input">
                        {{ errors.first("confirm_email") }}
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label"
                        >Name of Dentist Requested (optional)
                    </label>
                    <input
                        type="text"
                        class="input--style-4"
                        name="dentist"
                        v-model="school.requested_dentist"
                    />
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label"
                        >Please indicate the number of classes and students that
                        you would like to participate in Brush-a-mania:</label
                    >
                </div>
                <div class="col-md-6">
                    <label class="label"
                        >Total Number of Classes (required)
                    </label>
                    <input
                        type="text"
                        class="input--style-4"
                        name="number_of_classes"
                        required
                        v-validate="'numeric|min_value:1'"
                        data-vv-as="total number of classes"
                        v-model="school.number_of_classes"
                    />
                    <div class="invalid-input">
                        {{ errors.first("number_of_classes") }}
                        {{
                            backend_errors != null
                                ? backend_errors.number_of_classes != null
                                    ? backend_errors.number_of_classes[0]
                                    : null
                                : null
                        }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="label"
                        >Total Number of Students (required)</label
                    >
                    <input
                        type="text"
                        class="input--style-4"
                        name="number_of_students"
                        required
                        v-validate="'numeric|min_value:1'"
                        data-vv-as="total number of students"
                        v-model="school.number_of_students"
                    />
                    <div class="invalid-input">
                        {{ errors.first("number_of_students") }}
                        {{
                            backend_errors != null
                                ? backend_errors.number_of_students != null
                                    ? backend_errors.number_of_students[0]
                                    : null
                                : null
                        }}
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label"
                        >Enter the date and time of your school presentation
                        only if confirmed with your dentist.</label
                    >
                    <label class="label">
                        <b
                            >(All supplies required to run the program will be
                            delivered to participating schools the week of March
                            18, 2025.)</b
                        ></label
                    >
                    <input
                        type="datetime-local"
                        class="input--style-4"
                        name="presentation_date"
                        v-model="school.presentation_date"
                    />
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <div class="label">
                        For more information, contact Jennifer Boyd at:
                        <a href="mailto:jennifer@brushamania.ca"
                            >jennifer@brushamania.ca</a
                        >
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <google-re-captcha-v3
                        ref="captcha"
                        v-model="school.gRecaptchaResponse"
                        :siteKey="siteKey"
                        :id="'school_registration_id'"
                        :inline="true"
                        :action="'school_registration'"
                    >
                    </google-re-captcha-v3>
                </div>
            </div>
            <div class="p-t-15">
                <button
                    class="btn btn--radius-2 btn--blue pull-right"
                    type="submit"
                >
                    Submit
                </button>
            </div>

            <div
                class="success-message"
                v-if="successful != null && successful == true"
            >
                School Registered Successfully
            </div>
            <div
                class="error-message"
                v-else-if="successful != null && successful == false"
            >
                {{ error_message }}
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            school: {
                name: null,
                address: null,
                city: null,
                province: "ON",
                postal_code: null,
                phone: null,
                is_phone_valid: null,
                contact_salutation: "Ms.",
                contact_firstname: null,
                contact_lastname: null,
                email: null,
                email_confirmation: null,
                requested_dentist: null,
                number_of_classes: null,
                number_of_students: null,
                presentation_date: null,
                invite_token: null,
                old_invite_token: null,
                gRecaptchaResponse: null,
            },
            read_only: false,
            schools: [],
            successful: null,
            error_message: null,
            backend_errors: null,
            siteKey: "6LcyOrYqAAAAACLkY9tYPLxrWqh2Qg-gQE9JKfLO",
            registration_is_closed: false,
        };
    },
    created() {
        this.school.old_invite_token = new URL(location.href).searchParams.get(
            "invite_token"
        );
        this.getAllSchools();
    },
    mounted() {
        this.checkRegistrationClosure();
    },
    watch: {
        read_only: function (val) {
            if (!val) {
                this.school.name = "";
                this.school.address = "";
                this.school.city = "";
                this.school.province = "";
                this.school.is_phone_valid = null;
                this.school.postal_code = "";
                this.school.phone = "";
            }
        },
    },
    methods: {
        search(input) {
            this.school.name = input;
            if (input.length < 1) {
                return [];
            }
            let tempSchools = this.schools.filter((school) => {
                return school.name
                    .toLowerCase()
                    .startsWith(input.toLowerCase());
            });
            return tempSchools.map((tempSchool) => {
                return tempSchool;
            });
        },
        getAllSchools() {
            axios
                .get("/school/registration/all")
                .then((response) => {
                    this.schools = response.data.schools;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        // checkRegistrationClosure() {
        //     axios
        //         .get("/check-registration-closure")
        //         .then((response) => {
        //             if (response.data.status) {
        //                 // $("#close-registration-modal").modal("show");
        //             }
        //         })
        //         .catch((error) => {
        //             console.log(error);
        //         });
        // },
        autofillSchool(result) {
            axios
                .get("/school/autofill/" + result.city + "/" + result.name)
                .then((response) => {
                    this.read_only = true;
                    this.school.name = response.data.school.name;
                    this.school.address = response.data.school.address;
                    this.school.city = response.data.school.city;
                    this.school.province = response.data.school.province;
                    this.school.is_phone_valid = true;
                    this.school.postal_code = response.data.school.postal_code;
                    this.school.phone = response.data.school.phone;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        updatePhone(e) {
            console.log(e.isValid);
            this.school.is_phone_valid = e.isValid;
        },
        submitSchool() {
            this.school.old_invite_token = new URL(
                location.href
            ).searchParams.get("invite_token");
            this.$validator.validate().then((result) => {
                if (result) {
                    if (this.school.is_phone_valid == true) {
                        axios
                            .post("/school", this.school)
                            .then((response) => {
                                console.log(response.data);
                                this.successful = true;
                                console.log(this.successful);
                                this.resetData();
                                this.$refs.captcha.execute();
                                console.log(this.successful);
                            })
                            .catch((error) => {
                                this.successful = false;
                                console.log(this.successful);
                                this.error_message = error.response.data.msg;
                                if (error.response.data.msg != null) {
                                    this.error_message =
                                        error.response.data.msg;
                                } else {
                                    this.error_message =
                                        error.response.data.message;
                                }
                                this.backend_errors =
                                    error.response.data.errors;
                                this.$refs.captcha.execute();
                            });
                    }
                }
            });
        },
        getResultValue(result) {
            return result.name;
        },
        resetData() {
            this.school = {
                name: null,
                address: null,
                city: null,
                province: "Ontario",
                postal_code: null,
                phone: null,
                is_phone_valid: null,
                contact_salutation: "Ms.",
                contact_firstname: null,
                contact_lastname: null,
                email: null,
                email_confirmation: null,
                requested_dentist: null,
                number_of_classes: null,
                number_of_students: null,
                presentation_date: null,
                gRecaptchaResponse: null,
                old_invite_token: null,
            };
            this.$validator.pause();
            this.$nextTick(() => {
                this.$validator.errors.clear();
                this.$validator.fields.items.forEach((field) => field.reset());
                this.$validator.fields.items.forEach((field) =>
                    this.errors.remove(field)
                );
                this.$validator.resume();
            });
            console.log(this.successful);
        },
    },
};
</script>
