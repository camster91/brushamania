/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
// import Routes from './routes'
// import VueRouter from 'vue-router';
import "core-js/stable";
import "regenerator-runtime/runtime";
import "intersection-observer";
import BootstrapVue from "bootstrap-vue";
import VeeValidate from "vee-validate";
import VuePhoneNumberInput from "vue-phone-number-input";
import Autocomplete from "@trevoreyre/autocomplete-vue";
import "vue-phone-number-input/dist/vue-phone-number-input.css";
import "@trevoreyre/autocomplete-vue/dist/style.css";

// import {VueReCaptcha} from 'vue-recaptcha-v3';

require("./regbootstrap");

import Vue from "vue";

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use(VeeValidate, { fieldsBagName: "formFields" });
Vue.use(Autocomplete);
// Vue.use(VueReCaptcha, { siteKey: '6LcyOrYqAAAAACLkY9tYPLxrWqh2Qg-gQE9JKfLO' });

Vue.component("vue-phone-number", VuePhoneNumberInput);
Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);
Vue.component(
    "logos-row-component",
    require("./components/LogosRowComponent.vue").default
);
Vue.component(
    "parent-login-logos-row-component",
    require("./components/ParentLoginLogosRowComponent.vue").default
);
Vue.component(
    "school-registration-component",
    require("./registration/SchoolRegistrationComponent.vue").default
);
Vue.component(
    "dentist-registration-component",
    require("./registration/DentistRegistrationComponent.vue").default
);
Vue.component(
    "rotarian-registration-component",
    require("./registration/RotarianRegistrationComponent.vue").default
);
Vue.component(
    "parent-registration-component",
    require("./registration/ParentRegistrationComponent.vue").default
);
Vue.component(
    "politician-registration-component",
    require("./registration/PoliticianRegistrationComponent.vue").default
);

Vue.component(
    "google-re-captcha-v3",
    require("./components/googlerecaptchav3/GoogleReCaptchaV3.vue").default
);

Vue.component(
    "school-detail-component",
    require("./admin/school/SchoolDetailComponent.vue").default
);
Vue.component(
    "school-public-details-component",
    require("./admin/school/SchoolPublicDetailsComponent.vue").default
);
Vue.component(
    "dentist-detail-component",
    require("./admin/dentist/DentistDetailComponent.vue").default
);
Vue.component(
    "rotarian-detail-component",
    require("./admin/rotarian/RotarianDetailComponent.vue").default
);
Vue.component(
    "modal-component",
    require("./admin/components/ModalComponent.vue").default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#reg-container",
});
