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
						<h4 class="card-title">{{(politician != null) ? politician.politician_name : ''}}</h4>
						<button type="button" class="btn btn-success pull-right" @click="update=true" v-if="!update && getRole =='admin'">Update</button>
						<button type="button" class="btn btn-warning pull-right" @click="update=false" v-else-if="update && getRole =='admin'">Cancel</button>
					</div>
					<politician-update-component v-if="update" :role="getRole"></politician-update-component>
					<politician-detail-component :politician="politician" v-else></politician-detail-component>
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
				politician: null,
				update: false
			}
		},
		created() {
			this.getPolitician();
			eventBus.$on('refreshPolitician', () => {
				this.getPolitician();
			});
		},
		methods: {
			getPolitician() {
            	axios.get('/politician/' + this.$route.params.politician).then((response) => {
					this.politician = response.data.politician;
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