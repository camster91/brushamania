<style scoped>
.progress,
.progress-bar {
    height: 35px;
}
.progress-bar {
    color: black;
    font-size: 20px;
}
.children-container {
    margin-bottom: 10px;
}
.is_eligible {
    cursor: pointer;
}
</style>
<template>
    <div class="card-body">
        <b-table
            responsive
            :items="items"
            :fields="fields"
            :filter="search"
            :per-page="per_page"
            :currentPage="current_page"
            @filtered="onFiltered"
        >
            <template
                v-slot:cell(email_name)="data"
                v-if="title == 'Autoreply Emails List'"
            >
                <div>
                    {{ data.value }}
                    <br />
                    <router-link
                        :to="'/autoreply-emails/update/' + data.item.url_slug"
                        class="edit-link"
                        >Edit</router-link
                    >
                </div>
            </template>
            <template
                v-slot:cell(template_name)="data"
                v-if="title == 'Mail Blast List'"
            >
                <div>
                    {{ data.value }}
                    <br />
                    <router-link
                        :to="'/mailblast/update/' + data.item.url_slug"
                        class="edit-link"
                        >Edit</router-link
                    >
                    <button
                        class="delete-link"
                        @click="deleteMailBlast(data.item.id)"
                        style="padding-left: 0px !important"
                    >
                        Delete
                    </button>
                </div>
            </template>
            <template
                v-slot:cell(politician_name)="data"
                v-if="title == 'Politicians List'"
            >
                <div>
                    {{ data.value }}
                    <br />
                    <router-link
                        :to="'/politicians/' + data.item.url_slug"
                        class="edit-link"
                        >Edit</router-link
                    >
                    <button
                        class="delete-link"
                        @click="deletePolitician(data.item.id)"
                        style="padding-left: 0px !important"
                    >
                        Delete
                    </button>
                </div>
            </template>
            <template v-slot:cell(name)="data" v-if="title == 'Users List'">
                <div>
                    {{ data.value }}
                    <br />
                    <button
                        class="delete-link"
                        @click="deleteUser(data.item.id)"
                        style="padding-left: 0px !important"
                    >
                        Delete
                    </button>
                </div>
            </template>
            <template
                v-slot:cell(school_name)="data"
                v-if="title == 'Schools List'"
            >
                <div>
                    <router-link :to="'/schools/' + data.item.url_slug">{{
                        data.value
                    }}</router-link>
                    <br />
                    <router-link
                        :to="
                            '/schools/' +
                            data.item.url_slug +
                            '/' +
                            selected_year
                        "
                        class="edit-link"
                        >Edit</router-link
                    >
                    <router-link
                        :to="
                            '/schools/presentation/' +
                            data.item.url_slug +
                            '/' +
                            selected_year
                        "
                        class="presentation-link"
                        v-if="data.item.presentation != null"
                        >Presentation</router-link
                    >
                    <button
                        class="delete-link"
                        @click="deleteSchool(data.item.id)"
                    >
                        Delete
                    </button>
                </div>
            </template>

            <template
                v-slot:cell(presentation.dentist)="data"
                v-if="title == 'Schools List'"
            >
                <router-link
                    v-if="data.item.presentation != null"
                    :to="'/dentists/' + data.item.presentation.dentist_url_slug"
                    >{{ data.value }}</router-link
                >
            </template>

            <template
                v-slot:cell(presentation.rotarian)="data"
                v-if="title == 'Schools List'"
            >
                <router-link
                    v-if="data.item.presentation != null"
                    :to="
                        '/dentists/' + data.item.presentation.rotarian_url_slug
                    "
                    >{{ data.value }}</router-link
                >
            </template>

            <template
                v-slot:cell(presentation.presentation_date)="data"
                v-if="title == 'Schools List'"
            >
                <template v-if="data.item.presentation != null">
                    <template
                        v-if="data.item.presentation.presentation_date != null"
                        >{{ data.value }}</template
                    >
                    <template v-else>To be Determined</template>
                </template>
                <template v-else> Not Yet Registered </template>
            </template>

            <template
                v-slot:cell(dentist_name)="data"
                v-if="title == 'Dentists List'"
            >
                <div>
                    <router-link :to="'/dentists/' + data.item.url_slug">{{
                        data.value
                    }}</router-link>
                    <br />
                    <router-link
                        :to="'/dentists/' + data.item.url_slug"
                        class="edit-link"
                        >Edit</router-link
                    >
                    <button
                        class="delete-link"
                        @click="deleteDentist(data.item.id)"
                    >
                        Delete
                    </button>
                </div>
            </template>
            <template
                v-slot:cell(assigned_school)="data"
                v-if="title == 'Dentists List' || title == 'Rotarians List'"
            >
                <div
                    v-for="presentation in data.item.presentations"
                    style="margin-bottom: 10px"
                >
                    <router-link
                        :to="
                            '/schools/' +
                            presentation.url_slug +
                            '/' +
                            selected_year
                        "
                        >{{ presentation.school }}</router-link
                    >
                </div>
            </template>
            <template
                v-slot:cell(assigned_school_presentation_date)="data"
                v-if="title == 'Dentists List' || title == 'Rotarians List'"
            >
                <div
                    v-for="presentation in data.item.presentations"
                    style="margin-bottom: 10px"
                >
                    <template v-if="presentation != null">
                        <template
                            v-if="presentation.presentation_date != null"
                            >{{ presentation.presentation_date }}</template
                        >
                        <template v-else>To be Determined</template>
                    </template>
                    <template v-else> Not Yet Registered </template>
                </div>
            </template>

            <template
                v-slot:cell(rotarian_name)="data"
                v-if="title == 'Rotarians List'"
            >
                <div>
                    <router-link :to="'/rotarians/' + data.item.url_slug">{{
                        data.value
                    }}</router-link>
                    <br />
                    <router-link
                        :to="'/rotarians/' + data.item.url_slug"
                        class="edit-link"
                        >Edit</router-link
                    >
                    <button
                        class="delete-link"
                        @click="deleteRotarian(data.item.id)"
                    >
                        Delete
                    </button>
                </div>
            </template>

            <template
                v-slot:cell(parent_name)="data"
                v-if="title == 'Parents List'"
            >
                <div>
                    <router-link :to="'/parents/' + data.item.url_slug">{{
                        titleCase(data.value)
                    }}</router-link>
                    <br />
                    <router-link
                        :to="'/parents/' + data.item.url_slug"
                        class="edit-link"
                        >Edit</router-link
                    >
                    <router-link
                        :to="'/parents/add-children/' + data.item.url_slug"
                        class="edit-link"
                        >Add Children</router-link
                    >
                    <button
                        class="delete-link"
                        @click="deleteParent(data.item.id)"
                    >
                        Delete
                    </button>
                </div>
            </template>
            <template
                v-slot:cell(children)="data"
                v-if="title == 'Parents List'"
            >
                <div
                    v-for="child in data.value"
                    class="children-container"
                    :key="child.url_slug"
                >
                    <router-link :to="'/children/' + child.url_slug">
                        {{ titleCase(child.firstname) }}
                    </router-link>
                    <b-progress>
                        <b-progress-bar
                            :value="child.brush_count"
                            :max="100"
                            animated
                            :label="`${child.brush_count}%`"
                            variant="success"
                        ></b-progress-bar>
                    </b-progress>
                </div>
            </template>

            <template
                v-slot:cell(child_name)="data"
                v-if="title == 'Children List'"
            >
                <div>
                    <router-link :to="'/children/' + data.item.url_slug">{{
                        titleCase(data.value)
                    }}</router-link>
                    <br />
                    <router-link
                        :to="'/children/brushtracker/' + data.item.url_slug"
                        class="edit-link"
                        >Brushtracker</router-link
                    >
                    <router-link
                        :to="'/children/' + data.item.url_slug"
                        class="edit-link"
                        >Edit</router-link
                    >
                    <button
                        class="delete-link"
                        @click="deleteChild(data.item.id)"
                    >
                        Delete
                    </button>
                </div>
            </template>
            <template
                v-slot:cell(child_parent.name)="data"
                v-if="title == 'Children List'"
            >
                <router-link
                    :to="'/parents/' + data.item.child_parent.url_slug"
                    >{{ data.value }}</router-link
                >
            </template>
            <template
                v-slot:cell(brush_count)="data"
                v-if="title == 'Children List'"
            >
                <b-progress>
                    <b-progress-bar
                        :value="data.value"
                        :max="100"
                        animated
                        :label="`${data.value}%`"
                        variant="success"
                    ></b-progress-bar>
                </b-progress>
            </template>
            <template
                v-slot:cell(certificate_status)="data"
                v-if="title == 'Children List'"
            >
                <div class="cert_status">
                    <span
                        class="is_eligible"
                        v-if="data.value"
                        @click.prevent="sendCertificate(data.item.id)"
                        >Eligible</span
                    >
                    <span class="not_eligible" v-if="!data.value"
                        >Not Eligible</span
                    >
                </div>
            </template>
        </b-table>
        <b-row>
            <b-col md="6" class="my-1">
                <b-pagination
                    :total-rows="total_items"
                    :per-page="per_page"
                    v-model="current_page"
                    class="my-0"
                />
            </b-col>
        </b-row>
    </div>
</template>

<script>
import { eventBus } from "../../app";

export default {
    data() {
        return {
            current_page: 1,
            per_page: 10,
            total_items: 0,
            is_updated: false,
        };
    },
    props: {
        search: String,
        fields: Array,
        items: Array,
        total_rows: Number,
        title: String,
        selected_year: Number,
    },
    created() {
        console.log("total items 1", this.total_rows);
        if (this.total_rows > 0) {
            console.log("total items 2", this.total_rows);
            this.total_items = this.total_rows;
            this.is_updated = true;
        }
    },
    updated() {
        console.log("total items 3", this.total_rows);
        // if(!this.is_updated) {
        console.log("total items 4", this.total_rows);
        this.total_items = this.total_rows;
        this.is_updated = true;
        // }
    },
    methods: {
        onFiltered(filteredItems) {
            console.log("total items 5", filteredItems.length);
            this.total_items = filteredItems.length;
            this.currentPage = 1;
        },
        deleteSchool(id) {
            eventBus.$emit("deleteSchool", id);
        },
        deleteDentist(id) {
            eventBus.$emit("deleteDentist", id);
        },
        deleteRotarian(id) {
            eventBus.$emit("deleteRotarian", id);
        },
        deleteParent(id) {
            eventBus.$emit("deleteParent", id);
        },
        deleteChild(id) {
            eventBus.$emit("deleteChild", id);
        },
        deletePolitician(id) {
            eventBus.$emit("deletePolitician", id);
        },
        deleteMailBlast(id) {
            eventBus.$emit("deleteMailBlast", id);
        },
        deleteUser(id) {
            eventBus.$emit("deleteUser", id);
        },
        sendCertificate(id) {
            console.log("send cert");
            eventBus.$emit("sendCertificate", id);
        },
        titleCase(str) {
            return str
                .toLowerCase()
                .split(" ")
                .map(function (word) {
                    return word.charAt(0).toUpperCase() + word.slice(1);
                })
                .join(" ");
        },
    },
};
</script>
