<template>
	<div class="card-body">
		<slot></slot>
		<div class="table-responsive">
			<table class="table table-borderless">
				<tbody>
					<tr>
						<td>
							<b>Rotarian Name</b>
						</td>
						<td>
							{{(rotarian != null) ? rotarian.rotarian_name : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Rotary Club Name</b>
						</td>
						<td>
							{{(rotarian != null) ? rotarian.club_name : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Phone</b>
						</td>
						<td>
							{{(rotarian != null) ? rotarian.phone : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Email</b>
						</td>
						<td>
							{{(rotarian != null) ? rotarian.email : ''}}
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
			rotarian: Object
		}
	}
</script>