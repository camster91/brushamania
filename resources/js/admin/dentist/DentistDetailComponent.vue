<template>
	<div class="card-body">
		<slot></slot>
		<div class="table-responsive">
			<table class="table table-borderless">
				<tbody>
					<tr>
						<td>
							<b>Dentist Name</b>
						</td>
						<td>
							{{(dentist != null) ? dentist.dentist_name : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Clinic Name</b>
						</td>
						<td>
							{{(dentist != null) ? dentist.clinic : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Contact Name</b>
						</td>
						<td>
							{{(dentist != null) ? dentist.contact : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Address</b>
						</td>
						<td>
							{{(dentist != null) ? dentist.address : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>City</b>
						</td>
						<td>
							{{(dentist != null) ? dentist.city : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Province</b>
						</td>
						<td>
							{{(dentist != null) ? dentist.province : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Postal Code</b>
						</td>
						<td>
							{{(dentist != null) ? dentist.postal_code : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Website</b>
						</td>
						<td>
							<!-- {{(dentist != null) ? dentist.website : ''}} -->
							<a :href="(dentist != null) ? getWebsite : '#'" target="_blank">{{(dentist != null) ? dentist.website : ''}}</a>
						</td>
					</tr>
					<tr>
						<td>
							<b>Work Phone</b>
						</td>
						<td>
							{{(dentist != null) ? dentist.phone : ''}}
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
			dentist: Object
		},
		computed: {
			getWebsite() {
				if(this.dentist.website != null) {
					let sites = this.dentist.website.split('://');
					if(sites.length == 2) {
						return this.dentist.website;
					}
					return '//' + this.dentist.website;
				}
				return '#';
				
			}
		}
	}
</script>