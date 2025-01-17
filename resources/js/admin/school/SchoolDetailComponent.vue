<style scoped>
	#guest-col {
		vertical-align: baseline !important;
	}
	.guest-name {
		margin-bottom: 5px;
	}
</style>
<template>
	<div class="card-body">
		<slot></slot>
		<div class="table-responsive">
			<table class="table table-borderless">
				<tbody>
					<tr>
						<td>
							<b>School Name</b>
						</td>
						<td>
							{{(school != null) ? school.school_name : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Address</b>
						</td>
						<td>
							{{(school != null) ? school.address : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>City</b>
						</td>
						<td>
							{{(school != null) ? school.city : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Province</b>
						</td>
						<td>
							{{(school != null) ? school.province : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Postal Code</b>
						</td>
						<td>
							{{(school != null) ? school.postal_code : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Phone</b>
						</td>
						<td>
							{{(school != null) ? school.phone : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>School Map</b>
						</td>
						<td>
							<a :href="(school != null) ? 'https://www.google.com/maps/search/' + (school.school_name + ' ' + school.city).toLowerCase().replaceAll(' ', '+'): ''" target="_blank">{{(school != null) ? 'https://www.google.com/maps/search/' + (school.school_name + ' ' + school.city).toLowerCase().replaceAll(' ', '+'): ''}}</a>
						</td>
					</tr>
					<tr>
						<td>
							<b>School Contact Name</b>
						</td>
						<td>
							{{(school != null) ? school.school_contact : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>School Email</b>
						</td>
						<td>
							<a :href="(school != null) ? 'mailto: '+ school.email : '' ">{{(school != null) ? school.email : ''}}</a>
						</td>
					</tr>
					<tr>
						<td>
							<b>Number of Classes</b>
						</td>
						<td>
							{{(school != null) ? school.number_of_classes : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Number of Students</b>
						</td>
						<td>
							{{(school != null) ? school.number_of_students : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>School Presentation Date and Time</b>
						</td>
						<td>
							{{(school != null) ? (school.presentation_date != null) ? school.presentation_date : '' : null}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Dentist Name</b>
						</td>
						<td>
							{{(school != null) ? school.dentist_name : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Dentist Contact Name</b>
						</td>
						<td>
							{{(school != null) ? school.dentist_contact : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Dentist Email</b>
						</td>
						<td>
							<a :href="(school != null) ? 'mailto: '+ school.dentist_email : '' ">{{(school != null) ? school.dentist_email : ''}}</a>
						</td>
					</tr>
					<tr>
						<td>
							<b>Dentist Phone Number</b>
						</td>
						<td>
							{{(school != null) ? school.dentist_phone : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Rotarian Name</b>
						</td>
						<td>
							{{(school != null) ? school.rotarian_name : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Rotarian Email</b>
						</td>
						<td>
							<a :href="(school != null) ? 'mailto: '+ school.rotarian_email : '' ">{{(school != null) ? school.rotarian_email : ''}}</a>
						</td>
					</tr>
					<tr>
						<td>
							<b>Rotarian Phone Number</b>
						</td>
						<td>
							{{(school != null) ? school.rotarian_phone : ''}}
						</td>
					</tr>
					<tr>
						<td id="guest-col">
							<b>Guests</b>
						</td>
						<td>
							<div v-if="school != null">
								<p v-for="(guest, index) in school.guests" :key="index" class="guest-name">{{guest}}</p>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			school: Object
		},
		computed: {
			getWebsite() {
				if(this.school.dentist_website != null) {
					let sites = this.school.dentist_website.split('://');
					if(sites.length == 2) {
						return this.school.dentist_website;
					}
					return '//' + this.school.dentist_website;
				}
				return '#';
				
			}
		}
	}
</script>