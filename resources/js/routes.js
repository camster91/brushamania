import HomeComponent from "./admin/dashboard/HomeComponent.vue";
import SchoolListComponent from "./admin/school/SchoolListComponent.vue";
import SchoolNewComponent from "./admin/school/SchoolNewComponent.vue";
import SchoolNewUnregisteredComponent from "./admin/school/SchoolNewUnregisteredComponent.vue";
import SchoolComponent from "./admin/school/SchoolComponent.vue";
import SchoolPresentationComponent from "./admin/school/SchoolPresentationComponent.vue";
import DentistListComponent from "./admin/dentist/DentistListComponent.vue";
import DentistNewComponent from "./admin/dentist/DentistNewComponent.vue";
import DentistComponent from "./admin/dentist/DentistComponent.vue";
import RotarianListComponent from "./admin/rotarian/RotarianListComponent.vue";
import RotarianNewComponent from "./admin/rotarian/RotarianNewComponent.vue";
import RotarianComponent from "./admin/rotarian/RotarianComponent.vue";
import ParentListComponent from "./admin/parent/ParentListComponent.vue";
import ParentNewComponent from "./admin/parent/ParentNewComponent.vue";
import ParentComponent from "./admin/parent/ParentComponent.vue";
import ParentChildComponent from "./admin/parent/ParentChildComponent.vue";
import ChildrenListComponent from "./admin/children/ChildrenListComponent.vue";
import AdminListComponent from "./admin/user/AdminListComponent.vue";
import PoliticianListComponent from "./admin/politician/PoliticianListComponent";
import PoliticianComponent from "./admin/politician/PoliticianComponent";
import MyChildrenComponent from "./admin/children/MyChildrenComponent.vue";
import AddChildrenComponent from "./admin/children/AddChildrenComponent.vue";
import ChildComponent from "./admin/children/ChildComponent.vue";
import BrushtrackerComponent from "./admin/brushtracker/BrushtrackerComponent.vue";
import ChildBrushtrackerComponent from "./admin/children/ChildBrushtrackerComponent.vue";
import ProfileComponent from "./admin/profile/ProfileComponent.vue";
import MailBlastListComponent from "./admin/mailblast/MailBlastListComponent.vue";
import MailBlastNewComponent from "./admin/mailblast/MailBlastNewComponent.vue";
import AutoreplyListComponent from "./admin/autoreply/AutoreplyListComponent.vue";
import AutoreplyEditComponent from "./admin/autoreply/AutoreplyEditComponent.vue";

var routes = [
    { path: "/", component: HomeComponent },
    { path: "/home", component: HomeComponent },
    { path: "/schools", component: SchoolListComponent },
    { path: "/schools/new", component: SchoolNewComponent },
    {
        path: "/schools/new-unregistered",
        component: SchoolNewUnregisteredComponent,
    },
    {
        path: "/schools/presentation/:school/:year?",
        component: SchoolPresentationComponent,
    },
    { path: "/schools/:school/:year?", component: SchoolComponent },
    { path: "/dentists", component: DentistListComponent },
    { path: "/dentists/new", component: DentistNewComponent },
    { path: "/dentists/:dentist", component: DentistComponent },
    { path: "/rotarians", component: RotarianListComponent },
    { path: "/rotarians/new", component: RotarianNewComponent },
    { path: "/rotarians/:rotarian", component: RotarianComponent },
    { path: "/parents", component: ParentListComponent },
    { path: "/parents/new", component: ParentNewComponent },
    { path: "/parents/add-children/:parent", component: ParentChildComponent },
    { path: "/parents/:parent", component: ParentComponent },
    { path: "/children", component: ChildrenListComponent },
    { path: "/politicians", component: PoliticianListComponent },
    { path: "/politicians/:politician", component: PoliticianComponent },
    { path: "/users/admin", component: AdminListComponent },
    { path: "/my-children", component: MyChildrenComponent },
    { path: "/add-children", component: AddChildrenComponent },
    { path: "/children/:child", component: ChildComponent },
    {
        path: "/children/brushtracker/:child",
        component: ChildBrushtrackerComponent,
    },
    { path: "/brush-tracker", component: BrushtrackerComponent },
    { path: "/profile", component: ProfileComponent },
    { path: "/mailblast", component: MailBlastListComponent },
    { path: "/mailblast/new", component: MailBlastNewComponent },
    { path: "/mailblast/update/:mailblast", component: MailBlastNewComponent },
    { path: "/autoreply-emails", component: AutoreplyListComponent },
    {
        path: "/autoreply-emails/update/:autoreply",
        component: AutoreplyEditComponent,
    },
];

export default routes;
