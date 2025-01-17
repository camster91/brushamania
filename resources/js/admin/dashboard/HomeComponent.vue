<template>
    <div class="main-panel">
        <header-title-component page_title="Dashboard"></header-title-component>
        <div class="content" v-if="getRole == 'admin' || getRole == 'student'">
        	<stats-row-component :year="getActiveYear" v-if="getRole == 'admin' || getRole == 'student'"></stats-row-component>
        	<div class="row" v-if="getRole == 'admin'"">
        		<school-presentation-list-component :year="getActiveYear"></school-presentation-list-component>
        		<complete-brushes-flosses-component :year="getActiveYear"></complete-brushes-flosses-component>
        	</div>
        </div>
		<school-content-component v-else-if="getRole == 'school'"></school-content-component>
		<!-- <dentist-content-component v-else-if="getRole == 'dentist'"></dentist-content-component> -->
		<presentation-component v-else-if="getRole == 'dentist' || getRole == 'rotarian'"></presentation-component>
    </div>
</template>

<script>
	import {mapGetters} from 'vuex';

	export default {
		beforeRouteEnter(to, from, next) {
			axios.get('/user/role').then((response) => {
				let role = response.data.role;
				axios.get('/user/url-slug').then((response) => {
					let url_slug = response.data.url_slug;
					if(role == 'parent') {
						next('/brush-tracker');
					}
					else {
						next();
					}
				}).catch((error) => {
					console.log(error);
				});
			}).catch((error) => {
				console.log(error);
			});
		},
		computed: {
			...mapGetters([
				'getActiveYear',
				'getRole'
			])
		}
	}
</script>