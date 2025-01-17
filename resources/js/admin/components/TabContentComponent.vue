<template>
	<div class="tab-pane fade" :class="is_active ? 'show active' : ''" :id="content_id" role="tabpanel" :aria-labelledby="content_label">
		<slot></slot>
	</div>
</template>

<script>
	export default {
		props: {
			is_active: Boolean,
			content_id: String,
			content_label: String
		}
	}
</script>