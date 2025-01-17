<style scoped>
	.card-title {
		display: inline;
	}
</style>

<template>
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">{{(dentist != null) ? dentist.dentist_name : ''}}</h4>
						<button type="button" class="btn btn-success pull-right" @click="update=true" v-if="!update && getRole =='admin'">Update</button>
						<button type="button" class="btn btn-warning pull-right" @click="update=false" v-else-if="update && getRole =='admin'">Cancel</button>
					</div>
					<dentist-update-component v-if="update" :role="getRole"></dentist-update-component>
					<dentist-detail-component :dentist="dentist" v-else></dentist-detail-component>
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
				dentist: null,
				update: false
			}
		},
		created() {
			this.getDentist();
			eventBus.$on('refreshDentist', () => {
				this.getDentist();
			});
		},
		methods: {
			getDentist() {
				axios.get('/user/role').then((response) => {
		            let role = response.data.role;
		            if(role == 'admin') {
		            	axios.get('/dentist/' + this.$route.params.dentist).then((response) => {
							this.dentist = response.data.dentist;
						}).catch((error) => {
							console.log(error);
						});
		            } else if(role == 'dentist') {
		            	axios.get('/user/url-slug').then((response) => {
							let url_slug = response.data.url_slug;
							axios.get('/dentist/' + url_slug).then((response) => {
								this.dentist = response.data.dentist;
							}).catch((error) => {
								console.log(error);
							});
						}).catch((error) => {
							console.log(error);
						});
		            }
		        }).catch((error) => {
		            console.log(error);
		        });
				
			}
		},
		computed: {
			...mapGetters([
				'getRole'
			])
		}
	}
</script>