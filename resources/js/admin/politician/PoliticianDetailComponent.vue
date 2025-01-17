<template>
	<div class="card-body">
		<slot></slot>
		<div class="table-responsive">
			<table class="table table-borderless">
				<tbody>
					<tr>
						<td>
							<b>Politician Name</b>
						</td>
						<td>
							{{(politician != null) ? politician.politician_name : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Title</b>
						</td>
						<td>
							{{(politician != null) ? politician.title : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Constituency</b>
						</td>
						<td>
							{{(politician != null) ? politician.constituency : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>City</b>
						</td>
						<td>
							{{(politician != null) ? politician.city : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Province</b>
						</td>
						<td>
							{{(politician != null) ? politician.province : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Email</b>
						</td>
						<td>
							{{(politician != null) ? politician.email : ''}}
						</td>
					</tr>
					<tr>
						<td>
							<b>Phone</b>
						</td>
						<td>
							{{(politician != null) ? politician.phone : ''}}
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
			politician: Object
		}
	}
</script>