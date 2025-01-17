<template>
	<div class="main-panel">
		<header-title-component page_title="Autoreply Emails">
        </header-title-component>
        <content-component title="Autoreply Emails List" search_placeholder="Search Autoreply Email" :fields="fields" :items="getAutoreplyEmails" :total_rows="getAutoreplyEmailsLength"></content-component>
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
						key: 'email_name',
						sortable: true,
						label: 'Email Name'
					}
				]
			}
		},
		computed: {
			...mapGetters([
				'getAutoreplyEmails',
				'getAutoreplyEmailsLength'
			])
		}
	}
</script>