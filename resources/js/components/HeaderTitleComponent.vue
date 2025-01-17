<template>
	<nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
		<div class="container-fluid">
			<div class="navbar-wrapper">
				<div class="navbar-toggle">
					<button type="button" class="navbar-toggler">
						<span class="navbar-toggler-bar bar1"></span>
						<span class="navbar-toggler-bar bar2"></span>
						<span class="navbar-toggler-bar bar3"></span>
					</button>
				</div>
				<a href="#" class="navbar-brand">{{page_title}}</a>
				<slot name="export"></slot>
				<slot name="masterList"></slot>
				<slot name="import"></slot>
			</div>
			<slot name="year"></slot>
			<slot name="addNewUnregistered"></slot>
			<slot name="addNew"></slot>
		</div>
	</nav>
</template>

<script>
	export default {
		props: {
			page_title: String
		}
	}
</script>