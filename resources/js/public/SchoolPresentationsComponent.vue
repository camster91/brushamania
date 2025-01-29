<template>
	<div class="card-body">
		<h2 class="title">{{school.school_name}}</h2>
		<div class="table-responsive">
			<table class="table table-borderless">
				<tbody>
					<tr>
						<td>
							<b>School Name</b>
						</td>
						<td>
							{{school.school_name ? school.school_name : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Address</b>
						</td>
						<td>
							{{school.address ? school.address : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>City</b>
						</td>
						<td>
							{{school.city ? school.city : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Postal Code</b>
						</td>
						<td>
							{{school.postal_code ? school.postal_code : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Phone</b>
						</td>
						<td>
							{{school.phone ? school.phone : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>School Contact Name</b>
						</td>
						<td>
							{{school.school_contact ? school.school_contact : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Number of Classes</b>
						</td>
						<td>
							{{school.number_of_classes ? school.number_of_classes : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Number of Students</b>
						</td>
						<td>
							{{school.number_of_students ? school.number_of_students : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>School Presentation Date and Time</b>
						</td>
						<td>
							{{school.presentation_date ? school.presentation_date : 'To be Determined'}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Dentist Name</b>
						</td>
						<td>
							{{school.dentist_name ? school.dentist_name : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Dentist Phone Number</b>
						</td>
						<td>
							{{school.dentist_phone ? school.dentist_phone : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Dentist Website</b>
						</td>
						<td>
							<a :href="school.dentist_website">{{school.dentist_website}}</a>
						</td>
					</tr>
					<tr>
						<td>
							<b>Rotarian Name</b>
						</td>
						<td>
							{{school.rotarian_name ? school.rotarian_name : ''}}
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</template>