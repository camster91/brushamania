<template>
	<div class="card-body">
		<ul class="nav nav-tabs" id="myTab">
			<tab-component link="#profile" :is_active="true" tab_id="profile-tab" controls="profile" title="Update Profile" toggle="tab"></tab-component>
			<tab-component link="#password" :is_active="false" tab_id="password-tab" controls="password" title="Update Password" toggle="tab"></tab-component>
		</ul>
		<div class="tab-content" id="myTabContent">
			<tab-content-component :is_active="true" content_id="profile" content_label="profile-tab">
				<update-parent-profile-form-component v-if="getRole == 'parent'"></update-parent-profile-form-component>
				<update-rotarian-profile-form-component v-else-if="getRole == 'rotarian'"></update-rotarian-profile-form-component>
				<update-school-profile-form-component v-else-if="getRole == 'school'"></update-school-profile-form-component>
				<update-dentist-profile-form-component v-else-if="getRole == 'dentist'"></update-dentist-profile-form-component>
				<update-profile-form-component v-else-if="getRole == 'admin' || getRole == 'student'"></update-profile-form-component>
			</tab-content-component>
			<tab-content-component :is_active="false" content_id="password" content_label="password-tab">
				<update-password-form-component></update-password-form-component>
			</tab-content-component>
		</div>
	</div>
</template>

<script>
	import {mapGetters} from 'vuex';
	
	export default {
		computed: {
			...mapGetters([
				'getRole'
			])
		},
	}
</script>