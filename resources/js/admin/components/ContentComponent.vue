<style scoped>
	.card-title {
		display: inline;
	}
	.search-box {
		width: 30%;
	}

	@media(max-width: 768px) {
		.search-box {
			width: 50%;
		}
	}
</style>

<template>
	<div class="content">
    	<div class="row">
    		<div class="col-md-12">
    			<div class="card">
    				<div class="card-header">
    					<h4 class="card-title">{{title}}</h4>
    					<input type="text" class="form-control pull-right search-box" :placeholder="search_placeholder" v-model="search">
    				</div>
    				<list-component :search="search" :fields="fields" :items="items" :total_rows="total_rows" :title="title" :selected_year="selected_year"></list-component>
    			</div>
    		</div>
    	</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				search: ''
			}
		},
		props: {
			title: String,
			search_placeholder: String,
			fields: Array,
			items: Array,
			total_rows: Number,
			selected_year: Number
		}
	}
</script>