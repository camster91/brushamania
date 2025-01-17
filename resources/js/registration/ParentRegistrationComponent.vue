<style scoped>
.parent-registration-text .title {
    color: #0c71c3;
}
</style>

<template>
    <div class="card-body">
        <logos-row-component></logos-row-component>
        <div class="row parent-registration-text">
            <h1 class="title">
                THANK YOU FOR PARTICIPATING IN BRUSH-A-MANIA 2025.
            </h1>
            <a
                href="https://brushamania.ca/wp-content/uploads/sites/2/2024/08/Parent-instructions.pdf"
                target="_blank"
                ><h2 class="title">Parent Instructions PDF</h2></a
            >
            <h1 class="title">CONTEST OPEN APRIL 1 TO MAY 31</h1>
            <h1 class="title">FOR ALL ELEMENTARY STUDENTS UP TO GRADE 6</h1>
        </div>
        <h2 class="title">Parent Registration</h2>
        <h1 class="title" style="color: #0c71c3">
            Brush Tracking login for parents who have already registered. (<a
                href="/parent-login"
                style="color: #0c71c3"
                >Click here.</a
            >)
        </h1>
        <form @submit.prevent="submitParent" method="POST">
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">Parent's First Name (required)</label>
                    <input
                        type="text"
                        class="input--style-4"
                        name="parent_firstname"
                        required
                        v-validate
                        data-vv-as="parent first name"
                        v-model="parent.contact_firstname"
                    />
                    <div class="invalid-input">
                        {{ errors.first("parent_firstname") }}
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
                    <label class="label">Parent's Last Name (required) </label>
                    <input
                        type="text"
                        class="input--style-4"
                        name="parent_lastname"
                        required
                        v-validate
                        data-vv-as="parent last name"
                        v-model="parent.contact_lastname"
                    />
                    <div class="invalid-input">
                        {{ errors.first("parent_lastname") }}
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
                    <label class="label">Email (required) </label>
                    <input
                        type="email"
                        class="input--style-4"
                        name="email"
                        required
                        v-validate
                        v-model="parent.email"
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
                    <label class="label">Confirm Email (required)</label>
                    <input
                        type="email"
                        class="input--style-4"
                        name="confirm_email"
                        required
                        v-validate="'confirmed:email'"
                        data-vv-as="email confirmation"
                        v-model="parent.email_confirmation"
                    />
                    <div class="invalid-input">
                        {{ errors.first("confirm_email") }}
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-6">
                    <label class="label">Telephone Number (required) </label>
                    <vue-phone-number
                        v-model="parent.phone"
                        default-country-code="CA"
                        :no-country-selector="true"
                        :required="true"
                        @update="updatePhone"
                    ></vue-phone-number>
                    <div
                        class="invalid-input"
                        v-if="parent.is_phone_valid == false"
                        style="margin-top: 10px"
                    >
                        Phone number is invalid
                    </div>
                    <!-- <input type="tel" class="input--style-4" name="phone" required v-validate placeholder="(123) 456-7890" v-model="parent.phone">
                    <div class="invalid-input">
                        {{errors.first('phone')}}
                        {{(backend_errors != null) ? (backend_errors.phone != null) ? backend_errors.phone[0] : null : null}}
                    </div> -->
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label">Children</label>
                </div>
            </div>
            <div
                class="row row-space"
                v-for="(child, index) in parent.children"
                :key="index"
            >
                <div class="col-md-5">
                    <label class="label">First Name (required) </label>
                    <input
                        type="text"
                        class="input--style-4"
                        :name="'firstname' + index"
                        required
                        v-validate
                        data-vv-as="child first name"
                        v-model="child.firstname"
                    />
                    <div class="invalid-input">
                        {{ errors.first("firstname" + index) }}
                    </div>
                </div>
                <div class="col-md-5">
                    <label class="label">Last Name (required) </label>
                    <input
                        type="text"
                        class="input--style-4"
                        :name="'lastname' + index"
                        required
                        v-validate
                        data-vv-as="child first name"
                        v-model="child.lastname"
                    />
                    <div class="invalid-input">
                        {{ errors.first("lastname" + index) }}
                    </div>
                </div>
                <div class="col-md-2 btn-container">
                    <button
                        type="button"
                        class="btn btn-circle add-btn pull-right"
                        @click="addChild()"
                    >
                        <i class="fa fa-plus"></i>
                    </button>
                    <button
                        type="button"
                        class="btn btn-circle remove-btn pull-right"
                        @click="removeChild(index)"
                    >
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <google-re-captcha-v3
                        ref="captcha"
                        v-model="parent.gRecaptchaResponse"
                        :siteKey="siteKey"
                        :id="'parent_registration_id'"
                        :inline="true"
                        :action="'parent_registration'"
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
                Registered Successfully
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
            parent: {
                contact_firstname: null,
                contact_lastname: null,
                email: null,
                email_confirmation: null,
                phone: null,
                is_phone_valid: null,
                children: [
                    {
                        firstname: null,
                        lastname: null,
                    },
                ],
                gRecaptchaResponse: null,
            },
            successful: null,
            error_message: null,
            backend_errors: null,
            siteKey: "6LcyOrYqAAAAACLkY9tYPLxrWqh2Qg-gQE9JKfLO",
        };
    },
    methods: {
        submitParent() {
            console.log('submitting')
            this.$validator.validate()
            .then((result) => {
                console.log('result')
                if (result) {
                    if (this.parent.is_phone_valid == true) {
                        axios
                            .post("/parent", this.parent)
                            .then((response) => {
                                console.log(response.data);
                                this.successful = true;
                                this.resetData();
                                this.$refs.captcha.execute();
                            })
                            .catch((error) => {
                                this.successful = false;
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
                    } else {
                        console.log('phone number is invalid');
                    }
                } else {
                    console.log('no result');
                }
            })
        .catch((error) => {
            console.log('error');
            console.log(error);
        });
        },
        updatePhone(e) {
            console.log(e.isValid);
            this.parent.is_phone_valid = e.isValid;
        },
        addChild() {
            this.parent.children.push({
                firstname: null,
                lastname: null,
            });
        },
        removeChild(id) {
            if (this.parent.children.length > 1) {
                this.parent.children.splice(id, 1);
            }
        },
        resetData() {
            this.parent = {
                contact_firstname: null,
                contact_lastname: null,
                email: null,
                email_confirmation: null,
                phone: null,
                is_phone_valid: null,
                children: [
                    {
                        firstname: null,
                        lastname: null,
                    },
                ],
                gRecaptchaResponse: null,
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
        },
    },
};
</script>
