<template>
	<div class="main-panel">
		<header-title-component page_title="Mail Blast">
			<template v-slot:addNew>
        		<router-link class="btn btn-primary" to="/mailblast/new">Add New Mail Blast</router-link >
        	</template>
        </header-title-component>
        <content-component title="Mail Blast List" search_placeholder="Search Mail Blast" :fields="fields" :items="getMailBlasts" :total_rows="getMailBlastsLength"></content-component>
	</div>
</template>

<script>
	import {mapGetters} from 'vuex';
	import {eventBus} from '../../app';

	export default {
		data() {
			return {
				search: '',
				fields: [
					{
						key: 'template_name',
						sortable: true,
						label: 'Template Name'
					},
					{
						key: 'receiver',
						sortable: true,
						label: 'Receiver'
					},
					{
						key: 'date_created',
						sortable: true,
						label: 'Date Created'
					}
				]
			}
		},
		created() {
			eventBus.$on('deleteMailBlast', (id) => {
				this.deleteMailBlast(id);
			});
		},
		computed: {
			...mapGetters([
				'getMailBlasts',
				'getMailBlastsLength'
			])
		},
		methods: {
			deleteMailBlast(id) {
				swal({
					title: "Are you sure?",
					text: "Once deleted, you will never be able to recover this mail blast!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((willDelete) => {
					if(willDelete) {
						axios.delete('/mailblast/' + id).then((response) => {
							swal(response.data.msg, {
								icon: 'success'
							});
							this.$store.dispatch('getMailBlasts');
						}).catch((error) => {
							swal(error.response.data.msg, {
								icon: 'error'
							});
						});
					}
				});
			}
		}
	}
</script>