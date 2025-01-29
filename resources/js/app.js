/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import "core-js/stable";
import "regenerator-runtime/runtime";
import VueRouter from "vue-router";
import Routes from "./routes";
import BootstrapVue from "bootstrap-vue";
import VeeValidate from "vee-validate";
import VueEditor from "vue2-editor";
import VuePhoneNumberInput from "vue-phone-number-input";
import "vue-phone-number-input/dist/vue-phone-number-input.css";

require("./bootstrap");

import { store } from "./store";

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

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component("vue-phone-number", VuePhoneNumberInput);
Vue.component(
    "header-title-component",
    require("./components/HeaderTitleComponent.vue").default
);
Vue.component(
    "sidebar-component",
    require("./components/SidebarComponent.vue").default
);
Vue.component(
    "content-component",
    require("./admin/components/ContentComponent.vue").default
);
Vue.component(
    "list-component",
    require("./admin/components/ListComponent.vue").default
);
Vue.component(
    "presentation-component",
    require("./admin/dashboard/PresentationComponent.vue").default
);

Vue.component(
    "school-content-component",
    require("./admin/school/SchoolContentComponent.vue").default
);
Vue.component(
    "school-detail-component",
    require("./admin/school/SchoolDetailComponent.vue").default
);
Vue.component(
    "school-update-component",
    require("./admin/school/SchoolUpdateComponent.vue").default
);

Vue.component(
    "dentist-content-component",
    require("./admin/dentist/DentistContentComponent.vue").default
);
Vue.component(
    "dentist-detail-component",
    require("./admin/dentist/DentistDetailComponent.vue").default
);
Vue.component(
    "dentist-update-component",
    require("./admin/dentist/DentistUpdateComponent.vue").default
);

Vue.component(
    "rotarian-detail-component",
    require("./admin/rotarian/RotarianDetailComponent.vue").default
);
Vue.component(
    "rotarian-update-component",
    require("./admin/rotarian/RotarianUpdateComponent.vue").default
);

Vue.component(
    "politician-content-component",
    require("./admin/politician/PoliticianContentComponent.vue").default
);
Vue.component(
    "politician-detail-component",
    require("./admin/politician/PoliticianDetailComponent.vue").default
);
Vue.component(
    "politician-update-component",
    require("./admin/politician/PoliticianUpdateComponent.vue").default
);

Vue.component(
    "parent-update-component",
    require("./admin/parent/ParentUpdateComponent.vue").default
);

Vue.component(
    "child-update-component",
    require("./admin/children/ChildUpdateComponent.vue").default
);
Vue.component(
    "my-children-tab-component",
    require("./admin/children/MyChildrenTabComponent.vue").default
);
Vue.component(
    "child-parent-update-component",
    require("./admin/children/ChildParentUpdateComponent.vue").default
);

Vue.component(
    "child-brushtracker-component",
    require("./admin/brushtracker/ChildBrushtrackerComponent.vue").default
);
Vue.component(
    "child-brush-tracker-check-boxes",
    require("./admin/brushtracker/ChildBrushtrackerCheckBoxes.vue").default
);

Vue.component(
    "stats-component",
    require("./admin/components/StatsComponent.vue").default
);
Vue.component(
    "stats-row-component",
    require("./admin/dashboard/StatsRowComponent.vue").default
);
Vue.component(
    "school-presentation-list-component",
    require("./admin/dashboard/SchoolPresentationListComponent.vue").default
);
Vue.component(
    "complete-brushes-flosses-component",
    require("./admin/dashboard/CompleteBrushesFlossesComponent.vue").default
);

Vue.component(
    "profile-tab-component",
    require("./admin/profile/ProfileTabComponent.vue").default
);
Vue.component(
    "tab-component",
    require("./admin/components/TabComponent.vue").default
);
Vue.component(
    "tab-content-component",
    require("./admin/components/TabContentComponent.vue").default
);
Vue.component(
    "modal-component",
    require("./admin/components/ModalComponent.vue").default
);

Vue.component(
    "update-parent-profile-form-component",
    require("./admin/profile/UpdateParentProfileFormComponent.vue").default
);
Vue.component(
    "update-rotarian-profile-form-component",
    require("./admin/profile/UpdateRotarianProfileFormComponent.vue").default
);
Vue.component(
    "update-school-profile-form-component",
    require("./admin/profile/UpdateSchoolProfileFormComponent.vue").default
);
Vue.component(
    "update-dentist-profile-form-component",
    require("./admin/profile/UpdateDentistProfileFormComponent.vue").default
);
Vue.component(
    "update-profile-form-component",
    require("./admin/profile/UpdateProfileFormComponent.vue").default
);
Vue.component(
    "update-password-form-component",
    require("./admin/profile/UpdatePasswordFormComponent.vue").default
);
Vue.component(
    "update-password-modal-component",
    require("./admin/profile/UpdatePasswordModalComponent.vue").default
);

Vue.component(
    "mail-form-component",
    require("./admin/mailblast/MailFormComponent.vue").default
);

Vue.component(
    "autoreply-form-component",
    require("./admin/autoreply/AutoreplyFormComponent.vue").default
);

Vue.use(VueRouter);

const router = new VueRouter({
    routes: Routes,
    mode: "history",
});

Vue.use(BootstrapVue);
Vue.use(VueEditor);
Vue.use(VeeValidate, { fieldsBagName: "formFields" });
// Vue.use(ValidationObserver);

export const eventBus = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app-wrapper",
    store: store,
    router: router,
    data: {
        admin_items: [
            {
                classicon: "nc-icon nc-bank",
                name: "Dashboard",
                link: "/home",
            },
            {
                classicon: "nc-icon nc-hat-3",
                name: "Schools",
                link: "/schools",
            },
            {
                classicon: "nc-icon nc-badge",
                name: "Dentists",
                link: "/dentists",
            },
            {
                classicon: "nc-icon nc-briefcase-24",
                name: "Rotarians",
                link: "/rotarians",
            },
            {
                classicon: "nc-icon nc-single-02",
                name: "Parents",
                link: "/parents",
            },
            {
                classicon: "nc-icon nc-satisfied",
                name: "Children",
                link: "/children",
            },
            {
                classicon: "nc-icon nc-briefcase-24",
                name: "Politicians",
                link: "/politicians",
            },
            {
                classicon: "nc-icon nc-email-85",
                name: "Mail Blast",
                link: "/mailblast",
            },
            {
                classicon: "nc-icon nc-email-85",
                name: "Autoreply Emails",
                link: "/autoreply-emails",
            },
            {
                classicon: "nc-icon nc-single-02",
                name: "Users",
                link: "/users/admin",
            },
            {
                classicon: "nc-icon nc-circle-10",
                name: "User Profile",
                link: "/profile",
            },
        ],
        parent_items: [
            {
                classicon: "nc-icon nc-tap-01",
                name: "Brush Tracker",
                link: "/brush-tracker",
            },
            {
                classicon: "nc-icon nc-satisfied",
                name: "Children",
                link: "/my-children",
            },
            {
                classicon: "nc-icon nc-circle-10",
                name: "User Profile",
                link: "/profile",
            },
        ],
        school_items: [
            {
                classicon: "nc-icon nc-bank",
                name: "Dashboard",
                link: "/home",
            },
            {
                classicon: "nc-icon nc-circle-10",
                name: "User Profile",
                link: "/profile",
            },
        ],
        dentist_items: [
            {
                classicon: "nc-icon nc-bank",
                name: "Dashboard",
                link: "/home",
            },
            {
                classicon: "nc-icon nc-circle-10",
                name: "User Profile",
                link: "/profile",
            },
        ],
        rotarian_items: [
            {
                classicon: "nc-icon nc-bank",
                name: "Dashboard",
                link: "/home",
            },
            {
                classicon: "nc-icon nc-circle-10",
                name: "User Profile",
                link: "/profile",
            },
        ],
        student_items: [
            {
                classicon: "nc-icon nc-bank",
                name: "Dashboard",
                link: "/home",
            },
            {
                classicon: "nc-icon nc-hat-3",
                name: "Schools",
                link: "/schools",
            },
            {
                classicon: "nc-icon nc-circle-10",
                name: "Politician",
                link: "/politicians",
            },
            {
                classicon: "nc-icon nc-circle-10",
                name: "User Profile",
                link: "/profile",
            },
        ],
    },
    beforeCreate() {
        axios
            .get("/user/role")
            .then((response) => {
                if (
                    response.data.role == "admin" ||
                    response.data.role == "student"
                ) {
                    axios
                        .get("/year/active")
                        .then((response) => {
                            let year = response.data.year;
                            this.$store.dispatch("getSchools", year);
                            this.$store.dispatch("getDentists", year);
                            this.$store.dispatch("getRotarians", year);
                            this.$store.dispatch("getParents", year);
                            this.$store.dispatch("getChildren", year);
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                    this.$store.dispatch("getPoliticians");
                    this.$store.dispatch("getAutoreplyEmails");
                    this.$store.dispatch("getMailBlasts");
                    this.$store.dispatch("getUsers");
                } else if (response.data.role == "student") {
                    this.$store.dispatch("getPoliticians");
                }
                this.$store.dispatch("getYears");
                this.$store.dispatch("getRole");
            })
            .catch((error) => {
                console.log(error);
            });
    },
});
