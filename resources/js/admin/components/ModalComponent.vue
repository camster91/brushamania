<template>
	<div class="modal fade" tabindex="-1" role="dialog" :id="modal_id">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{title}}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
				</div>
				<slot></slot>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			modal_id: String,
			title: String,
		}
	}
</script>