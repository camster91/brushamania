<template>
	<div class="col-div-5 col-md-6 col-sm-6">
		<div class="card card-stats">
			<div class="card-body">
				<div class="row">
					<div class="col-5 col-md-4">
						<div class="icon-big text-center icon-warning">
							<i class="nc-icon text-success" :class="icon"></i>
						</div>
					</div>
					<div class="col-7 col-md-8">
						<div class="numbers">
							<p class="card-category">{{category}}</p>
							<p class="card-title">{{title}}</p>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<hr>
				<div class="stats">
					<i class="fa fa-bookmark"></i> Registered for {{year}}
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			icon: String,
			category: String,
			title: Number,
			year: Number
		}
		
	}
</script>