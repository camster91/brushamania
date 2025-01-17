import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
	state: {
		schools: [],
		dentists: [],
		rotarians: [],
		parents: [],
		children: [],
		users: [],
		politicians: [],
		years: [],
		mailblasts: [],
		autoreply_emails: [],
		active_year: null,
		role: null
	},
	getters: {
		getSchools: state => {
			return state.schools;
		},
		getSchoolsLength: state => {
			return state.schools.length;
		},
		getStudentsCount: state => {
			let students = 0;
			state.schools.forEach(school => {
				students+=school.presentation.total_students;
			});
			// array.forEach(element => {
				
			// });
			return students;
		},
		getDentists: state => {
			return state.dentists;
		},
		getDentistsLength: state => {
			return state.dentists.length;
		},
		getRotarians: state => {
			return state.rotarians;
		},
		getRotariansLength: state => {
			return state.rotarians.length;
		},
		getParents: state => {
			return state.parents;
		},
		getParentsLength: state => {
			return state.parents.length;
		},
		getChildren: state => {
			return state.children;
		},
		getChildrenLength: state => {
			return state.children.length;
		},
		getUsers: state => {
			return state.users;
		},
		getUsersLength: state => {
			return state.users.length;
		},
		getPoliticians: state => {
			return state.politicians;
		},
		getPoliticiansLength: state => {
			return state.politicians.length;
		},
		getMailBlasts: state => {
			return state.mailblasts;
		},
		getMailBlastsLength: state => {
			return state.mailblasts.length;
		},
		getAutoreplyEmails: state => {
			return state.autoreply_emails;
		},
		getAutoreplyEmailsLength: state => {
			return state.autoreply_emails.length;
		},
		getYears: state => {
			return state.years;
		},
		getActiveYear: state => {
			return state.active_year;
		},
		getRole: state => {
			return state.role;
		}
	},
	mutations: {
		setSchools(state, schoolsData) {
			state.schools = schoolsData;
		},
		setDentists(state, dentistsData) {
			state.dentists = dentistsData;
		},
		setRotarians(state, rotariansData) {
			state.rotarians = rotariansData;
		},
		setParents(state, parentsData) {
			state.parents = parentsData;
		},
		setChildren(state, childrenData) {
			state.children = childrenData;
		},
		setUsers(state, usersData) {
			state.users = usersData;
		},
		setPoliticians(state, politiciansData) {
			state.politicians = politiciansData;
		},
		setMailBlasts(state, mailbastsData) {
			state.mailblasts = mailbastsData;
		},
		setAutoreplyEmails(state, autoreplyEmailsData) {
			state.autoreply_emails = autoreplyEmailsData;
		},
		setYears(state, yearsData) {
			state.years = yearsData;
		},
		setActiveYear(state, activeYearData) {
			state.active_year = activeYearData;
		},
		setRole(state, roleData) {
			state.role = roleData;
		}
	},
	actions: {
		getSchools({commit}, year) {
			axios.get('/schools/list/' + year).then((response) => {
				// console.log(response.data.schools);
				commit('setSchools', response.data.schools);
			}).catch((error) => {
				console.log(error);
			});
		},
		getDentists({commit}, year) {
			axios.get('/dentists/list/' + year).then((response) => {
				// console.log(response.data.dentists);
				commit('setDentists', response.data.dentists);
			}).catch((error) => {
				console.log(error);
			});
		},
		getRotarians({commit}, year) {
			axios.get('/rotarians/list/' + year).then((response) => {
				// console.log(response.data.rotarians);
				commit('setRotarians', response.data.rotarians);
			}).catch((error) => {
				console.log(error);
			});
		},
		getParents({commit}, year) {
			axios.get('/parents/list/' + year).then((response) => {
				// console.log(response.data.parents);
				commit('setParents', response.data.parents);
			}).catch((error) => {
				console.log(error);
			});
		},
		getChildren({commit}, year) {
			axios.get('/children/list/' + year).then((response) => {
				// console.log(response.data.children);
				commit('setChildren', response.data.children);
			}).catch((error) => {
				console.log(error);
			});
		},
		getUsers({commit}) {
			axios.get('/users/admin/list').then((response) => {
				// console.log(response.data.children);
				commit('setUsers', response.data.users);
			}).catch((error) => {
				console.log(error);
			});

		},
		getPoliticians({commit}) {
			axios.get('/politician/list').then((response) => {
				// console.log(response.data.children);
				commit('setPoliticians', response.data.politicians);
			}).catch((error) => {
				console.log(error);
			});

		},
		getMailBlasts({commit}) {
			axios.get('/mailblast/list').then((response) => {
				// console.log(response.data.children);
				commit('setMailBlasts', response.data.mailblasts);
			}).catch((error) => {
				console.log(error);
			});

		},
		getAutoreplyEmails({commit}) {
			axios.get('/autoreply-emails/list').then((response) => {
				// console.log(response.data.children);
				commit('setAutoreplyEmails', response.data.autoreply_emails);
			}).catch((error) => {
				console.log(error);
			});

		},
		getYears({commit}) {
			axios.get('/year/active').then((response) => {
				commit('setActiveYear', response.data.year);
				let years = [];
				for(let i = response.data.year; i >= 2015; i--) {
					years.push(i);
				}
				commit('setYears', years);
			}).catch((error) => {
				console.log(error);
			});

		},
		getRole({commit}) {
			axios.get('/user/role').then((response) => {
				commit('setRole', response.data.role);
			}).catch((error) => {
				console.log(error);
			});
		}
	}
});