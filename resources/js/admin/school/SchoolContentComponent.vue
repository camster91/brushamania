<style scoped>
	.card-title {
		display: inline;
	}
	.presentation-link {
		margin-right: 15px;
		color: #FFFFFF;
	}
</style>

<template>
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">{{(school != null) ? school.school_name : ''}}</h4>
						<button type="button" class="btn btn-success pull-right" @click="update=true" v-if="!update && getRole =='admin'">Update</button>
						<button type="button" class="btn btn-warning pull-right" @click="update=false" v-else-if="update && getRole =='admin'">Cancel</button>
						<router-link :to="'/schools/presentation/' + $route.params.school + '/' +  year" class="btn btn-primary pull-right presentation-link" v-if="getRole != null && getRole == 'admin'">School Presentation</router-link>
					</div>
					<school-update-component v-if="update" :role="getRole"></school-update-component>
					<school-detail-component :school="school" v-else></school-detail-component>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import {eventBus} from '../../app';
	import {mapGetters} from 'vuex';

	export default {
		data() {
			return {
				school: null,
				update: false,
				year: ''
			}
		},
		created() {
			this.getSchool();
			if(this.$route.params.year) {
				this.year = this.$route.params.year
			}
			eventBus.$on('refreshSchool', () => {
				this.getSchool();
			});
		},
		methods: {
			getSchool() {
				axios.get('/user/role').then((response) => {
					let role = response.data.role;
					if(role == 'admin') {
						axios.get('/school/' + this.$route.params.school + '/' + this.$route.params.year).then((response) => {
							this.school = response.data.school;
						}).catch((error) => {
							console.log(error);
						});
					} else if(role == 'school') {
						axios.get('/year/active').then((response) => {
							let year = response.data.year;
							axios.get('/user/url-slug').then((response) => {
								let url_slug = response.data.url_slug;
								axios.get('/school/' + url_slug + '/' + year).then((response) => {
									this.school = response.data.school;
								}).catch((error) => {
									console.log(error);
								});
							}).catch((error) => {
								console.log(error);
							});
						}).catch((error) => {
							console.log(error);
						});
					} else if (role == 'dentist' || role == 'rotarian') {
						axios.get('/year/active').then((response) => {
							axios.get('/school/' + this.$route.params.school + '/' + response.data.year).then((response) => {
								this.school = response.data.school;
							}).catch((error) => {
								console.log(error);
							})
						}).catch((error) => {
							console.log(error);
						})
					}
					
					
				}).catch((error) => {
					console.log(error);
				})
			}
		},
		computed: {
			...mapGetters([
				'getRole',
				'getActiveYear'
			])
		}
	}
</script>